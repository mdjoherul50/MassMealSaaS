<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Bazar; // Bazar মডেল ইম্পোর্ট করুন
use App\Models\User; // User মডেল ইম্পোর্ট করুন
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BazarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // TenantScope স্বয়ংক্রিয়ভাবে শুধু এই মেসের বাজার তালিকা আনবে
        $bazars = Bazar::with('buyer')->latest('date')->paginate(20);
        
        return view('tenant.bazars.index', compact('bazars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // এই মেসের 'bazarman' বা 'mess_admin' রোলের ইউজারদের আনুন, যেন ড্রপডাউনে দেখানো যায়
        $buyers = User::where('tenant_id', Auth::user()->tenant_id)
                      ->whereIn('role', ['bazarman', 'mess_admin'])
                      ->orderBy('name')
                      ->get();
                      
        return view('tenant.bazars.create', compact('buyers'));
    }

    /**
     * Store a new resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'buyer_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        // tenant_id যোগ করুন
        $validatedData['tenant_id'] = Auth::user()->tenant_id;

        Bazar::create($validatedData);

        return redirect()->route('bazars.index')->with('success', 'Bazar entry added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}