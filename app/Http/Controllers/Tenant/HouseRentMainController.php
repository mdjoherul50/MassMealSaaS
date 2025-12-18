<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\HouseRentMain;
use App\Models\HouseRent;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HouseRentMainController extends Controller
{
    public function index(Request $request)
    {
        $rents = HouseRentMain::orderByDesc('month')->paginate(20);

        $selectedMonth = $request->query('month');
        if (!$selectedMonth) {
            $selectedMonth = HouseRentMain::orderByDesc('month')->value('month');
        }

        $main = null;
        $members = collect();
        $memberRents = collect();

        $showInactive = $request->boolean('show_inactive');

        if ($selectedMonth) {
            $main = HouseRentMain::where('month', $selectedMonth)->first();
            $membersQuery = Member::orderBy('name');
            if (!$showInactive) {
                $membersQuery->where('is_active', true);
            }
            $members = $membersQuery->get();
            $memberRents = HouseRent::where('month', $selectedMonth)->get()->keyBy('member_id');
        }

        $months = HouseRentMain::orderByDesc('month')->pluck('month');

        return view('tenant.house_rent_mains.index', compact('rents', 'months', 'selectedMonth', 'main', 'members', 'memberRents', 'showInactive'));
    }

    public function create()
    {
        return view('tenant.house_rent_mains.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'month' => 'required|string|max:7|unique:house_rent_mains,month,NULL,id,tenant_id,' . Auth::user()->tenant_id,
            'house_rent' => 'required|numeric|min:0',
            'wifi_bill' => 'nullable|numeric|min:0',
            'current_bill' => 'nullable|numeric|min:0',
            'gas_bill' => 'nullable|numeric|min:0',
            'extra_bill' => 'nullable|numeric|min:0',
            'extra_note' => 'nullable|string|max:255',
            'status' => 'nullable|in:pending,paid,partial',
        ]);

        $data['tenant_id'] = Auth::user()->tenant_id;
        $data['created_by'] = Auth::id();

        $data['wifi_bill'] = $data['wifi_bill'] ?? 0;
        $data['current_bill'] = $data['current_bill'] ?? 0;
        $data['gas_bill'] = $data['gas_bill'] ?? 0;
        $data['extra_bill'] = $data['extra_bill'] ?? 0;
        $data['status'] = $data['status'] ?? 'pending';

        // If there is previous month with carry_forward, add it
        $previousMonth = Carbon::parse($data['month'] . '-01')->subMonth()->format('Y-m');
        $previous = HouseRentMain::where('month', $previousMonth)->first();
        if ($previous && $previous->carry_forward > 0) {
            $data['extra_bill'] += $previous->carry_forward;
        }

        HouseRentMain::create($data);

        return redirect()->route('house-rent-mains.index')->with('success', 'House rent main record created.');
    }

    public function edit(HouseRentMain $houseRentMain)
    {
        return view('tenant.house_rent_mains.edit', compact('houseRentMain'));
    }

    public function update(Request $request, HouseRentMain $houseRentMain)
    {
        $data = $request->validate([
            'house_rent' => 'required|numeric|min:0',
            'wifi_bill' => 'nullable|numeric|min:0',
            'current_bill' => 'nullable|numeric|min:0',
            'gas_bill' => 'nullable|numeric|min:0',
            'extra_bill' => 'nullable|numeric|min:0',
            'extra_note' => 'nullable|string|max:255',
            'status' => 'nullable|in:pending,paid,partial',
        ]);

        $data['wifi_bill'] = $data['wifi_bill'] ?? 0;
        $data['current_bill'] = $data['current_bill'] ?? 0;
        $data['gas_bill'] = $data['gas_bill'] ?? 0;
        $data['extra_bill'] = $data['extra_bill'] ?? 0;
        $data['status'] = $data['status'] ?? 'pending';

        $houseRentMain->update($data);

        return redirect()->route('house-rent-mains.index')->with('success', 'House rent main record updated.');
    }

    /**
     * Sync assigned_to_members from member house rents for a given month.
     */
    public function syncAssigned($month)
    {
        $assigned = HouseRent::where('month', $month)->sum('total');

        $main = HouseRentMain::where('month', $month)->first();
        if ($main) {
            $main->assigned_to_members = $assigned;
            $main->save(); // will recalc remaining_balance
        }

        return back()->with('success', 'Assigned amount synced.');
    }

    /**
     * Carry forward remaining balance to next month.
     */
    public function carryForward($month)
    {
        $main = HouseRentMain::where('month', $month)->first();
        if (!$main) {
            return back()->with('error', 'Main record not found.');
        }

        $nextMonth = Carbon::parse($month . '-01')->addMonth()->format('Y-m');

        $nextMain = HouseRentMain::where('month', $nextMonth)->first();
        if (!$nextMain) {
            return back()->with('error', 'Next month record not found. Create it first.');
        }

        $nextMain->extra_bill += $main->remaining_balance;
        $nextMain->save();

        $main->carry_forward = $main->remaining_balance;
        $main->save();

        return back()->with('success', 'Remaining balance carried forward to next month.');
    }

    public function saveMemberRents(Request $request, $month)
    {
        $data = $request->validate([
            'rents' => 'required|array',
            'rents.*.member_id' => 'required|integer|exists:members,id',
            'rents.*.house_rent' => 'nullable|numeric|min:0',
            'rents.*.wifi_bill' => 'nullable|numeric|min:0',
            'rents.*.current_bill' => 'nullable|numeric|min:0',
            'rents.*.gas_bill' => 'nullable|numeric|min:0',
            'rents.*.extra_bill' => 'nullable|numeric|min:0',
            'rents.*.extra_note' => 'nullable|string|max:255',
            'rents.*.paid_amount' => 'nullable|numeric|min:0',
            'rents.*.status' => 'nullable|in:pending,paid,partial',
            'rents.*.payment_method' => 'nullable|string|max:255',
            'rents.*.payment_date' => 'nullable|date',
        ]);

        $tenantId = Auth::user()->tenant_id;
        $userId = Auth::id();

        $main = HouseRentMain::where('month', $month)->first();
        if (!$main) {
            return back()->with('error', 'Main record not found for this month. Create it first.');
        }

        foreach ($data['rents'] as $row) {
            $houseRent = (float) ($row['house_rent'] ?? 0);
            $wifi = (float) ($row['wifi_bill'] ?? 0);
            $current = (float) ($row['current_bill'] ?? 0);
            $gas = (float) ($row['gas_bill'] ?? 0);
            $extra = (float) ($row['extra_bill'] ?? 0);
            $paid = (float) ($row['paid_amount'] ?? 0);

            $total = $houseRent + $wifi + $current + $gas + $extra;
            $due = max(0, $total - $paid);

            $status = $row['status'] ?? null;
            if (!$status) {
                if ($paid <= 0) {
                    $status = 'pending';
                } elseif ($paid >= $total) {
                    $status = 'paid';
                } else {
                    $status = 'partial';
                }
            }

            HouseRent::updateOrCreate(
                [
                    'tenant_id' => $tenantId,
                    'member_id' => $row['member_id'],
                    'month' => $month,
                ],
                [
                    'house_rent' => $houseRent,
                    'wifi_bill' => $wifi,
                    'current_bill' => $current,
                    'gas_bill' => $gas,
                    'extra_bill' => $extra,
                    'extra_note' => $row['extra_note'] ?? null,
                    'total' => $total,
                    'paid_amount' => $paid,
                    'due_amount' => $due,
                    'status' => $status,
                    'payment_method' => $row['payment_method'] ?? null,
                    'payment_date' => $row['payment_date'] ?? null,
                    'created_by' => $userId,
                ]
            );
        }

        $assigned = HouseRent::where('month', $month)->sum('total');
        $main->assigned_to_members = $assigned;
        $main->save();

        return back()->with('success', 'Member house rents saved.');
    }

    public function copyPreviousMonth(Request $request, $month)
    {
        $tenantId = Auth::user()->tenant_id;
        $userId = Auth::id();

        $main = HouseRentMain::where('month', $month)->first();
        if (!$main) {
            return back()->with('error', 'Main record not found for this month. Create it first.');
        }

        $previousMonth = Carbon::parse($month . '-01')->subMonth()->format('Y-m');

        $previousRents = HouseRent::where('month', $previousMonth)->get();
        if ($previousRents->isEmpty()) {
            return back()->with('error', 'No member rents found for previous month: ' . $previousMonth);
        }

        foreach ($previousRents as $rent) {
            HouseRent::updateOrCreate(
                [
                    'tenant_id' => $tenantId,
                    'member_id' => $rent->member_id,
                    'month' => $month,
                ],
                [
                    'house_rent' => $rent->house_rent,
                    'wifi_bill' => $rent->wifi_bill,
                    'current_bill' => $rent->current_bill,
                    'gas_bill' => $rent->gas_bill,
                    'extra_bill' => $rent->extra_bill,
                    'extra_note' => $rent->extra_note,
                    'total' => $rent->total,
                    'paid_amount' => 0,
                    'due_amount' => $rent->total,
                    'status' => 'pending',
                    'payment_method' => null,
                    'payment_date' => null,
                    'created_by' => $userId,
                ]
            );
        }

        $assigned = HouseRent::where('month', $month)->sum('total');
        $main->assigned_to_members = $assigned;
        $main->save();

        return back()->with('success', 'Previous month (' . $previousMonth . ') rents copied.');
    }
}
