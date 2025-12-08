<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Bazar;
use App\Models\Deposit;
use App\Models\Meal;
use App\Models\Member;
use App\Models\HouseRent;
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
        $memberId = $request->input('member_id');
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

        // 4. মোট ডিপোজিট (Total Deposits)
        $totalDeposits = Deposit::whereBetween('date', [$startDate, $endDate])->sum('amount');

        // 5. মোট হাউস রেন্ট + ইউটিলিটি (House Rent + utilities)
        $houseRentAgg = HouseRent::where('month', $selectedMonth)
            ->selectRaw('
                COALESCE(SUM(house_rent), 0) as house_rent_total,
                COALESCE(SUM(wifi_bill), 0) as wifi_total,
                COALESCE(SUM(current_bill), 0) as current_total,
                COALESCE(SUM(gas_bill), 0) as gas_total,
                COALESCE(SUM(extra_bill), 0) as extra_total,
                COALESCE(SUM(total), 0) as grand_total
            ')
            ->first();

        $totalHouseRent = $houseRentAgg->grand_total ?? 0;

        // 6. মেসের মোট চার্জ (Meal only)
        $totalCharge = $totalBazar;

        // 7. মেস ব্যালেন্স (Extra / Minus) - based only on meals
        $netBalance = $totalDeposits - $totalBazar;

        // --- প্রতিটি সদস্যের জন্য হিসাব ---
        $members = Member::orderBy('name')->get();
        $reportData = [];

        foreach ($members as $member) {
            if ($memberId && (int) $memberId !== (int) $member->id) {
                continue;
            }
            // সদস্যের মোট মিল
            $memberTotalMeal = Meal::where('member_id', $member->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->sum(DB::raw('breakfast + lunch + dinner'));

            // সদস্যের মোট ডিপোজিট
            $memberTotalDeposit = Deposit::where('member_id', $member->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('amount');

            // সদস্যের হাউস রেন্ট + ইউটিলিটি
            $memberHouseRent = HouseRent::where('member_id', $member->id)
                ->where('month', $selectedMonth)
                ->sum('total');

            // 4. সদস্যের মোট চার্জ (Meal + House Rent)
            $memberTotalCharge = ($memberTotalMeal * $avgMealRate) + $memberHouseRent;

            // 5. ব্যালেন্স (Balance)
            $balance = $memberTotalDeposit - $memberTotalCharge;

            $reportData[] = [
                'name' => $member->name,
                'total_meal' => $memberTotalMeal,
                'house_rent' => $memberHouseRent,
                'total_deposit' => $memberTotalDeposit,
                'total_charge' => $memberTotalCharge,
                'balance' => $balance,
            ];
        }

        return view('tenant.reports.overview', compact(
            'selectedMonth',
            'memberId',
            'totalBazar',
            'totalMeal',
            'avgMealRate',
            'totalDeposits',
            'totalHouseRent',
            'totalCharge',
            'netBalance',
            'reportData',
            'members'
        ));
    }
}