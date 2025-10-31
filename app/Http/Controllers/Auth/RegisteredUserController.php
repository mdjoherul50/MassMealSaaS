<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
   public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'mess_name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 'mess-admin' রোলটি খুঁজে বের করুন
        $messAdminRole = \App\Models\Role::where('slug', 'mess-admin')->first();
        if (! $messAdminRole) {
            // যদি কোনো কারণে রোলটি না থাকে (সিডার চালানো হয়নি)
            return back()->with('error', 'System role not found. Please contact admin.');
        }

        $user = null;
        try {
            DB::transaction(function () use ($request, $messAdminRole, &$user) {
                // ১. নতুন মেস (Tenant) তৈরি করুন
                $tenant = \App\Models\Tenant::create([
                    'name' => $request->mess_name,
                    'plan' => 'free',
                    'status' => 'active',
                ]);

                // ২. নতুন ইউজার (Mess Admin) তৈরি করুন
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role_id' => $messAdminRole->id, // নতুন role_id দিন
                    'tenant_id' => $tenant->id,
                ]);

                // ৩. টেন্যান্টের owner_user_id আপডেট করুন
                $tenant->owner_user_id = $user->id;
                $tenant->save();
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Registration failed. Please try again. ' . $e->getMessage());
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
