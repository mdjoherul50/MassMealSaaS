<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Meal; // Meal মডেল ইম্পোর্ট করুন
use App\Models\Member; // Member মডেল ইম্পোর্ট করুন
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // DB ইম্পোর্ট করুন
use Carbon\Carbon; // Carbon ইম্পোর্ট করুন

class MealController extends Controller
{
    /**
     * Show the form for bulk meal entry for a specific date.
     * (দৈনিক মিল এন্ট্রির পেজ দেখাবে)
     */
    public function bulkStoreView(Request $request)
    {
        // তারিখ ভ্যালিডেশন। যদি তারিখ না থাকে, আজকের তারিখ নেবে।
        $selectedDate = $request->input('date', Carbon::today()->format('Y-m-d'));

        // এই মেসের সব সদস্যকে আনুন
        // TenantScope স্বয়ংক্রিয়ভাবে কাজ করবে
        $members = Member::orderBy('name')->get();

        // ওই তারিখে সদস্যদের পূর্ববর্তী মিল ডেটা (যদি থাকে) আনুন
        $mealsData = Meal::where('date', $selectedDate)
                         ->pluck('data', 'member_id')
                         ->mapWithKeys(function ($data, $memberId) {
                             return [$memberId => [
                                 'breakfast' => $data['breakfast'] ?? 0,
                                 'lunch' => $data['lunch'] ?? 0,
                                 'dinner' => $data['dinner'] ?? 0,
                             ]];
                         });

        return view('tenant.meals.bulk', compact('members', 'mealsData', 'selectedDate'));
    }


    /**
     * Store bulk meal data for a specific date.
     * (দৈনিক মিল এন্ট্রির ডেটা সেভ করবে)
     */
    public function bulkStore(Request $request)
    {
        // তারিখ ভ্যালিডেশন
        $request->validate([
            'date' => 'required|date',
            'meals' => 'required|array',
            'meals.*.breakfast' => 'required|integer|min:0|max:10',
            'meals.*.lunch' => 'required|integer|min:0|max:10',
            'meals.*.dinner' => 'required|integer|min:0|max:10',
        ]);

        $tenantId = auth()->user()->tenant_id;
        $date = $request->input('date');
        $mealsInput = $request->input('meals');

        DB::transaction(function () use ($mealsInput, $tenantId, $date) {
            foreach ($mealsInput as $memberId => $counts) {
                // updateOrCreate ব্যবহার করে ডেটা সেভ বা আপডেট করবে
                Meal::updateOrCreate(
                    [
                        'tenant_id' => $tenantId,
                        'member_id' => $memberId,
                        'date' => $date,
                    ],
                    [
                        'breakfast' => $counts['breakfast'],
                        'lunch' => $counts['lunch'],
                        'dinner' => $counts['dinner'],
                        'created_by' => auth()->id(),
                        // 'data' কলামটি আমরা মাইগ্রেশন থেকে বাদ দিয়েছি, তাই এখানেও বাদ দিলাম
                    ]
                );
            }
        });

        return redirect()->route('meals.bulkStoreView', ['date' => $date])->with('success', 'Meals updated successfully!');
    }
}