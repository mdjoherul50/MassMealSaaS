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
    private function buildOverviewData(Request $request, $month = null): array
    {
        if ($month) {
            $currentDate = Carbon::parse($month . '-01');
        } else {
            $currentDate = Carbon::now();
        }

        $selectedMonth = $request->input('month', $currentDate->format('Y-m'));
        $memberId = $request->input('member_id');
        $startDate = Carbon::parse($selectedMonth . '-01')->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $totalBazar = Bazar::whereBetween('date', [$startDate, $endDate])->sum('total_amount');

        $totalMeal = Meal::whereBetween('date', [$startDate, $endDate])
            ->sum(DB::raw('breakfast + lunch + dinner'));

        $avgMealRate = ($totalMeal > 0) ? ($totalBazar / $totalMeal) : 0;

        $totalDeposits = Deposit::whereBetween('date', [$startDate, $endDate])->sum('amount');

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
        $totalCharge = $totalBazar;
        $netBalance = $totalDeposits - $totalBazar;

        $members = Member::orderBy('name')->get();
        $reportData = [];

        foreach ($members as $member) {
            if ($memberId && (int) $memberId !== (int) $member->id) {
                continue;
            }

            $memberTotalMeal = Meal::where('member_id', $member->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->sum(DB::raw('breakfast + lunch + dinner'));

            $memberTotalDeposit = Deposit::where('member_id', $member->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('amount');

            $memberHouseRent = HouseRent::where('member_id', $member->id)
                ->where('month', $selectedMonth)
                ->sum('total');

            $memberTotalCharge = ($memberTotalMeal * $avgMealRate) + $memberHouseRent;
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

        return compact(
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
        );
    }

    /**
     * Display the monthly overview report.
     * SRS সেকশন ১৬ ও ১৭ অনুযায়ী
     */
    public function overview(Request $request, $month = null)
    {
        $data = $this->buildOverviewData($request, $month);

        return view('tenant.reports.overview', $data);
    }

    public function overviewPdf(Request $request)
    {
        $data = $this->buildOverviewData($request);

        return view('pdf.monthly-overview', $data);
    }
}