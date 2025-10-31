<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Auth ইম্পোর্ট করুন
use Illuminate\Support\Facades\DB; // <-- এই লাইনটি যোগ করুন
use Carbon\Carbon; // <-- এই লাইনটি যোগ করুন

class MealController extends Controller
{

    /**
     * Show the form for bulk meal entry for a specific date.
     */
    public function bulkStoreView(Request $request)
    {
        // তারিখ ভ্যালিডেশন। যদি তারিখ না থাকে, আজকের তারিখ নেবে।
        $selectedDate = $request->input('date', Carbon::today()->format('Y-m-d'));

        // এই মেসের সব সদস্যকে আনুন
        $members = Member::orderBy('name')->get();

        // ওই তারিখে সদস্যদের পূর্ববর্তী মিল ডেটা (যদি থাকে) আনুন
        $mealsData = Meal::where('date', $selectedDate)
                         ->select('member_id', 'breakfast', 'lunch', 'dinner')
                         ->get()
                         ->keyBy('member_id'); // member_id দিয়ে collection-কে index করুন

        return view('tenant.meals.bulk-entry', compact('members', 'mealsData', 'selectedDate'));
    }


    /**
     * Store bulk meal data for a specific date. (সংশোধিত)
     */
    public function bulkStore(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'meals' => 'required|array',
            'meals.*.breakfast' => 'required|integer|min:0|max:10',
            'meals.*.lunch' => 'required|integer|min:0|max:10',
            'meals.*.dinner' => 'required|integer|min:0|max:10',
        ]);

        $tenantId = auth()->user()->tenant_id;
        $date = $request->input('date');

        DB::transaction(function () use ($request, $tenantId, $date) {
            foreach ($request->input('meals', []) as $memberId => $counts) {
                
                // --- এটিই হলো সমাধান ---
                // 'date' কলামটিকে প্রথম অ্যারেতে (খোঁজার অ্যারে) যোগ করা হয়েছে
                Meal::updateOrCreate(
                    [
                        'tenant_id' => $tenantId,
                        'member_id' => $memberId,
                        'date' => $date, // <-- এই লাইনটি এখানে যোগ করা হয়েছে
                    ],
                    [
                        'breakfast' => $counts['breakfast'],
                        'lunch' => $counts['lunch'],
                        'dinner' => $counts['dinner'],
                        'created_by' => auth()->id(),
                    ]
                );
            }
        });

        return redirect()->route('meals.bulkStoreView', ['date' => $date])->with('success', 'Meals updated successfully!');
    }

    public function index()
    {
        $meals = Meal::with('member') // সদস্যের নাম লোড করার জন্য
                     ->latest('date') // তারিখ অনুযায়ী সাজানো
                     ->paginate(20);
        
        return view('tenant.meals.index', compact('meals'));
    }

    /**
     * Show the form for creating a new resource. (নতুন মেথড)
     * (একটি নতুন মিল এন্ট্রি করার ফর্ম)
     */
    public function create()
    {
        $members = Member::orderBy('name')->get();
        return view('tenant.meals.create', compact('members'));
    }

    /**
     * Store a new resource in storage. (নতুন মেথড)
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'member_id' => 'required|exists:members,id',
            'date' => 'required|date',
            'breakfast' => 'required|integer|min:0|max:10',
            'lunch' => 'required|integer|min:0|max:10',
            'dinner' => 'required|integer|min:0|max:10',
        ]);

        $tenantId = Auth::user()->tenant_id;
        
        // updateOrCreate ব্যবহার করুন যেন ডুপ্লিকেট এন্ট্রি না হয়
        Meal::updateOrCreate(
            [
                'tenant_id' => $tenantId,
                'member_id' => $validatedData['member_id'],
                'date' => $validatedData['date'],
            ],
            [
                'breakfast' => $validatedData['breakfast'],
                'lunch' => $validatedData['lunch'],
                'dinner' => $validatedData['dinner'],
                'created_by' => Auth::id(),
            ]
        );

        return redirect()->route('meals.index')->with('success', 'Meal entry saved successfully.');
    }

    /**
     * Display the specified resource. (নতুন মেথড - আপনার অনুরোধে)
     */
    public function show(Meal $meal)
    {
        // TenantScope স্বয়ংক্রিয়ভাবে চেক করবে
        return view('tenant.meals.show', compact('meal'));
    }

    /**
     * Show the form for editing the specified resource. (নতুন মেথড)
     */
    public function edit(Meal $meal)
    {
        // TenantScope স্বয়ংক্রিয়ভাবে চেক করবে
        $members = Member::orderBy('name')->get();
        return view('tenant.meals.edit', compact('meal', 'members'));
    }

    /**
     * Update the specified resource in storage. (নতুন মেথড)
     */
    public function update(Request $request, Meal $meal)
    {
        $validatedData = $request->validate([
            'member_id' => 'required|exists:members,id',
            'date' => 'required|date',
            'breakfast' => 'required|integer|min:0|max:10',
            'lunch' => 'required|integer|min:0|max:10',
            'dinner' => 'required|integer|min:0|max:10',
        ]);
        
        // তারিখ পরিবর্তন করা হলে ডুপ্লিকেট এন্ট্রি চেক করুন
        $existing = Meal::where('member_id', $validatedData['member_id'])
                        ->where('date', $validatedData['date'])
                        ->where('id', '!=', $meal->id)
                        ->first();
        
        if ($existing) {
            return back()->withInput()->with('error', 'A meal entry for this member on this date already exists.');
        }

        $meal->update($validatedData);

        return redirect()->route('meals.index')->with('success', 'Meal entry updated successfully.');
    }

    /**
     * Remove the specified resource from storage. (নতুন মেথড)
     */
    public function destroy(Meal $meal)
    {
        // TenantScope স্বয়ংক্রিয়ভাবে চেক করবে
        $meal->delete();
        return redirect()->route('meals.index')->with('success', 'Meal entry deleted successfully.');
    }
}