<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\HouseRentMain;
use App\Models\HouseRent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HouseRentMainController extends Controller
{
    public function index()
    {
        $rents = HouseRentMain::orderByDesc('month')->paginate(20);
        return view('tenant.house_rent_mains.index', compact('rents'));
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
}
