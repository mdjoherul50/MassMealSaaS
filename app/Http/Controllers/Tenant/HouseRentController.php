<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\HouseRent;
use App\Models\HouseRentMain;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HouseRentController extends Controller
{
    public function index()
    {
        $rents = HouseRent::with('member')
            ->orderByDesc('month')
            ->paginate(20);

        return view('tenant.house_rents.index', compact('rents'));
    }

    public function create()
    {
        $members = Member::orderBy('name')->get();
        return view('tenant.house_rents.create', compact('members'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'month' => 'required|string|max:7',
            'member_id' => 'required|exists:members,id',
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

        $data['total'] = $data['house_rent']
            + $data['wifi_bill']
            + $data['current_bill']
            + $data['gas_bill']
            + $data['extra_bill'];

        $data['status'] = $data['status'] ?? 'pending';

        HouseRent::create($data);

        return redirect()->route('house-rents.index')
            ->with('success', 'House rent entry created.');
    }

    public function edit(HouseRent $houseRent)
    {
        $members = Member::orderBy('name')->get();
        return view('tenant.house_rents.edit', [
            'rent' => $houseRent,
            'members' => $members,
        ]);
    }

    public function update(Request $request, HouseRent $houseRent)
    {
        $data = $request->validate([
            'month' => 'required|string|max:7',
            'member_id' => 'required|exists:members,id',
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

        $data['total'] = $data['house_rent']
            + $data['wifi_bill']
            + $data['current_bill']
            + $data['gas_bill']
            + $data['extra_bill'];

        $data['status'] = $data['status'] ?? 'pending';

        $houseRent->update($data);

        return redirect()->route('house-rents.index')
            ->with('success', 'House rent entry updated.');
    }

    public function destroy(HouseRent $houseRent)
    {
        $houseRent->delete();

        return redirect()->route('house-rents.index')
            ->with('success', 'House rent entry deleted.');
    }

    public function myHouseRent()
    {
        // Find the member record for the current user (assuming user has a member record)
        $member = Member::where('tenant_id', Auth::user()->tenant_id)
            ->where('email', Auth::user()->email)
            ->first();

        if (!$member) {
            abort(403, 'Member record not found for this user.');
        }

        $rents = HouseRent::where('member_id', $member->id)
            ->orderByDesc('month')
            ->paginate(20);

        return view('tenant.house_rents.my', compact('rents'));
    }
}