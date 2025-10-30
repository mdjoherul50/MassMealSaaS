<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Bazar;
use App\Models\Deposit;
use App\Models\Meal;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display the monthly overview report.
     * SRS সেকশন ১৬ ও ১৭ অনুযায়ী
     */
    public function overview(Request $request, $month = null)
    {
        // মাস নির্ধারণ করা
        if ($month) {
            $currentDate = Carbon::parse($month . '-01');
        } else {
            $currentDate = Carbon::now();
        }
        
        $selectedMonth = $request->input('month', $currentDate->format('Y-m'));
        $startDate = Carbon::parse($selectedMonth . '-01')->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // --- ক্যালকুলেশন লজিক (SRS সেকশন ১৭) ---

        // 1. মোট বাজার (Total Bazar)
        $totalBazar = Bazar::whereBetween('date', [$startDate, $endDate])->sum('total_amount');

        // 2. মোট মিল (Total Meal) - আপনার অনুরোধ অনুযায়ী (sokal, dupur, rat)
        $totalMeal = Meal::whereBetween('date', [$startDate, $endDate])
                         ->sum(DB::raw('breakfast + lunch + dinner'));

        // 3. গড় মিল রেট (Avg Meal Rate)
        $avgMealRate = ($totalMeal > 0) ? ($totalBazar / $totalMeal) : 0;

        // --- প্রতিটি সদস্যের জন্য হিসাব ---
        $members = Member::orderBy('name')->get();
        $reportData = [];

        foreach ($members as $member) {
            // সদস্যের মোট মিল
            $memberTotalMeal = Meal::where('member_id', $member->id)
                                   ->whereBetween('date', [$startDate, $endDate])
                                   ->sum(DB::raw('breakfast + lunch + dinner'));
            
            // সদস্যের মোট ডিপোজিট
            $memberTotalDeposit = Deposit::where('member_id', $member->id)
                                         ->whereBetween('date', [$startDate, $endDate])
                                         ->sum('amount');
            
            // 4. সদস্যের মোট চার্জ (Member Charge)
            $memberTotalCharge = $memberTotalMeal * $avgMealRate;

            // 5. ব্যালেন্স (Balance)
            $balance = $memberTotalDeposit - $memberTotalCharge;

            $reportData[] = [
                'name' => $member->name,
                'total_meal' => $memberTotalMeal,
                'total_deposit' => $memberTotalDeposit,
                'total_charge' => $memberTotalCharge,
                'balance' => $balance,
            ];
        }

        return view('tenant.reports.overview', compact(
            'selectedMonth',
            'totalBazar',
            'totalMeal',
            'avgMealRate',
            'reportData'
        ));
    }
}