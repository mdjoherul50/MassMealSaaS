<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deposits = Deposit::with('member')->latest('date')->paginate(20);
        return view('tenant.deposits.index', compact('deposits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $members = Member::orderBy('name')->get();
        return view('tenant.deposits.create', compact('members'));
    }

    /**
     * Store a new resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric|min:0',
            'method' => 'nullable|string|max:50',
            'reference' => 'nullable|string|max:100',
        ]);

        $validatedData['tenant_id'] = Auth::user()->tenant_id;
        Deposit::create($validatedData);

        return redirect()->route('deposits.index')->with('success', 'Deposit entry added successfully.');
    }

    /**
     * Display the specified resource. (নতুন)
     */
    public function show(Deposit $deposit)
    {
        return view('tenant.deposits.show', compact('deposit'));
    }

    /**
     * Show the form for editing the specified resource. (নতুন)
     */
    public function edit(Deposit $deposit)
    {
        $members = Member::orderBy('name')->get();
        return view('tenant.deposits.edit', compact('deposit', 'members'));
    }

    /**
     * Update the specified resource in storage. (নতুন)
     */
    public function update(Request $request, Deposit $deposit)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric|min:0',
            'method' => 'nullable|string|max:50',
            'reference' => 'nullable|string|max:100',
        ]);

        $deposit->update($validatedData);
        return redirect()->route('deposits.index')->with('success', 'Deposit entry updated.');
    }

    /**
     * Remove the specified resource from storage. (নতুন)
     */
    public function destroy(Deposit $deposit)
    {
        $deposit->delete();
        return redirect()->route('deposits.index')->with('success', 'Deposit entry deleted.');
    }
}
