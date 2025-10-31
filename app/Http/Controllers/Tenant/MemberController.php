<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Member; // Member মডেল ইম্পোর্ট করুন
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Auth ইম্পোর্ট করুন
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // TenantScope স্বয়ংক্রিয়ভাবে শুধু এই মেসের সদস্যদের আনবে
        $members = Member::latest()->paginate(20);
        
        // ভিউ ফাইলটি আমরা পরের ধাপে তৈরি করছি
        return view('tenant.members.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ভিউ ফাইলটি আমরা পরের ধাপে তৈরি করছি
        return view('tenant.members.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class, // User টেবিলে ইউনিক
            'password' => ['required', 'confirmed', Rules\Password::defaults()], // পাসওয়ার্ড ভ্যালিডেশন
            'phone' => 'nullable|string|max:20',
            'join_date' => 'nullable|date',
        ]);

        // 'member' রোলটি খুঁজে বের করুন
        $memberRole = \App\Models\Role::where('slug', 'member')->first();
        if (! $memberRole) {
            return back()->with('error', 'Member role not found. Please run seeder.');
        }

        $tenantId = Auth::user()->tenant_id;

        try {
            DB::transaction(function () use ($validatedData, $tenantId, $memberRole, $request) {
                // ১. সদস্যের জন্য একটি User অ্যাকাউন্ট তৈরি করুন
                $user = User::create([
                    'name' => $validatedData['name'],
                    'email' => $validatedData['email'],
                    'password' => Hash::make($request->password), // ফর্ম থেকে পাসওয়ার্ড নিন
                    'role_id' => $memberRole->id,
                    'tenant_id' => $tenantId,
                ]);

                // ২. নতুন Member প্রোফাইল তৈরি করুন
                Member::create([
                    'tenant_id' => $tenantId,
                    'name' => $validatedData['name'],
                    'phone' => $validatedData['phone'],
                    'email' => $validatedData['email'],
                    'join_date' => $validatedData['join_date'],
                ]);
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create member. ' . $e->getMessage());
        }

        return redirect()->route('members.index')->with('success', 'Member added successfully (and user account created).');
    }
}