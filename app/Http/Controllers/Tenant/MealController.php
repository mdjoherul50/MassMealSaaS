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

        return view('tenant.meals.bulk', compact('members', 'mealsData', 'selectedDate'));
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
}