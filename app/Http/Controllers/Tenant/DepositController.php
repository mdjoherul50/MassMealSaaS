<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Deposit; // Deposit মডেল ইম্পোর্ট করুন
use App\Models\Member; // Member মডেল ইম্পোর্ট করুন
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // TenantScope স্বয়ংক্রিয়ভাবে শুধু এই মেসের ডিপোজিট তালিকা আনবে
        $deposits = Deposit::with('member')->latest('date')->paginate(20);
        
        return view('tenant.deposits.index', compact('deposits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // এই মেসের সব সদস্যকে আনুন
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

        // tenant_id যোগ করুন
        $validatedData['tenant_id'] = Auth::user()->tenant_id;

        Deposit::create($validatedData);

        return redirect()->route('deposits.index')->with('success', 'Deposit entry added successfully.');
    }

    // ... (show, edit, update, destroy মেথডগুলো আমরা পরে যোগ করবো)
}