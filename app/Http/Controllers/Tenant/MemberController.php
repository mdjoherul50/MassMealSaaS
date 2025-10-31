<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use App\Models\Role;
use App\Models\Meal;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str; // <-- Str ইম্পোর্ট করা হয়েছে (বাগ ফিক্স)
use Carbon\Carbon;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $members = Member::latest()->paginate(20);
        return view('tenant.members.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tenant.members.create');
    }

    /**
     * Store a new resource in storage. (সদস্যের লগইন পাসওয়ার্ড সহ)
     */
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
        $memberRole = Role::where('slug', 'member')->first();
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

    /**
     * Display the specified resource. (নতুন - সদস্যের স্টেটমেন্ট)
     */
    public function show(Request $request, Member $member)
    {
        // মাস নির্ধারণ করা
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $startDate = Carbon::parse($selectedMonth . '-01')->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // --- ঐ মাসের হিসাব ---
        
        // ১. মোট বাজার (Total Bazar)
        $totalBazar = \App\Models\Bazar::whereBetween('date', [$startDate, $endDate])->sum('total_amount');
        // ২. মোট মিল (Total Meal)
        $totalMeal = Meal::whereBetween('date', [$startDate, $endDate])->sum(DB::raw('breakfast + lunch + dinner'));
        // ৩. গড় মিল রেট (Avg Meal Rate)
        $avgMealRate = ($totalMeal > 0) ? ($totalBazar / $totalMeal) : 0;

        // --- এই সদস্যের হিসাব ---
        
        // সদস্যের মোট মিল (এই মাসে)
        $memberTotalMeal = Meal::where('member_id', $member->id)
                               ->whereBetween('date', [$startDate, $endDate])
                               ->sum(DB::raw('breakfast + lunch + dinner'));
        
        // সদস্যের মোট ডিপোজিট (এই মাসে)
        $memberTotalDeposit = Deposit::where('member_id', $member->id)
                                     ->whereBetween('date', [$startDate, $endDate])
                                     ->sum('amount');
        
        // সদস্যের মোট চার্জ (এই মাসে)
        $memberTotalCharge = $memberTotalMeal * $avgMealRate;
        // ব্যালেন্স (এই মাসে)
        $balance = $memberTotalDeposit - $memberTotalCharge;

        // তারিখ অনুযায়ী মিলের তালিকা
        $dateWiseMeals = Meal::where('member_id', $member->id)
                             ->whereBetween('date', [$startDate, $endDate])
                             ->orderBy('date', 'asc')
                             ->get();
                             
        // তারিখ অনুযায়ী ডিপোজিটের তালিকা
        $dateWiseDeposits = Deposit::where('member_id', $member->id)
                                  ->whereBetween('date', [$startDate, $endDate])
                                  ->orderBy('date', 'asc')
                                  ->get();

        return view('tenant.members.show', compact(
            'member', 
            'selectedMonth', 
            'avgMealRate', 
            'memberTotalMeal', 
            'memberTotalDeposit', 
            'memberTotalCharge', 
            'balance',
            'dateWiseMeals',
            'dateWiseDeposits'
        ));
    }

    /**
     * Show the form for editing the specified resource. (নতুন)
     */
    public function edit(Member $member)
    {
        return view('tenant.members.edit', compact('member'));
    }

    /**
     * Update the specified resource in storage. (নতুন)
     */
    public function update(Request $request, Member $member)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:users,email,' . $member->user_id, // User টেবিল চেক করুন
            'join_date' => 'nullable|date',
        ]);
        
        try {
            DB::transaction(function () use ($validatedData, $member) {
                // Member প্রোফাইল আপডেট করুন
                $member->update($validatedData);

                // সংশ্লিষ্ট User অ্যাকাউন্টও আপডেট করুন
                $user = User::where('email', $member->email)->first();
                if ($user) {
                    $user->update([
                        'name' => $validatedData['name'],
                        'email' => $validatedData['email'],
                    ]);
                }
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update member. ' . $e->getMessage());
        }

        return redirect()->route('members.index')->with('success', 'Member updated successfully.');
    }

    /**
     * Remove the specified resource from storage. (নতুন)
     */
    public function destroy(Member $member)
    {
        try {
            DB::transaction(function () use ($member) {
                // User অ্যাকাউন্ট ডিলিট করুন
                $user = User::where('email', $member->email)->first();
                if ($user) {
                    $user->delete();
                }
                // Member প্রোফাইল ডিলিট করুন
                $member->delete();
            });
        } catch (\Exception $e) {
            // Foreign key constraint error হতে পারে যদি মিল বা ডিপোজিট থাকে
            return back()->with('error', 'Cannot delete member. They have existing meals or deposits. Please delete them first.');
        }

        return redirect()->route('members.index')->with('success', 'Member deleted successfully.');
    }
}