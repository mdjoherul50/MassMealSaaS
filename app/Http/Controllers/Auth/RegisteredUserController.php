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
            'mess_name' => ['required', 'string', 'max:255'], // নতুন ভ্যালিডেশন
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // SRS অনুযায়ী ট্রানজেকশন ব্যবহার করা ভালো
        $user = null;
        try {
            DB::transaction(function () use ($request, &$user) {
                // ১. নতুন মেস (Tenant) তৈরি করুন
                $tenant = \App\Models\Tenant::create([
                    'name' => $request->mess_name,
                    'plan' => 'free', // ডিফল্ট প্ল্যান
                    'status' => 'active',
                ]);

                // ২. নতুন ইউজার (Mess Admin) তৈরি করুন
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'mess_admin', // SRS অনুযায়ী রোল সেট করুন
                    'tenant_id' => $tenant->id, // নতুন টেন্যান্টের ID দিন
                ]);

                // ৩. টেন্যান্টের owner_user_id আপডেট করুন
                $tenant->owner_user_id = $user->id;
                $tenant->save();
            });
        } catch (\Exception $e) {
            // কোনো কারণে ফেইল হলে
            return back()->with('error', 'Registration failed. Please try again.');
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
