<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Member; // Member মডেল ইম্পোর্ট করুন
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Auth ইম্পোর্ট করুন

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // TenantScope স্বয়ংক্রিয়ভাবে শুধু এই মেসের সদস্যদের আনবে
        $members = Member::latest()->paginate(20);
        
        // ভিউ ফাইলটি আমরা পরের ধাপে তৈরি করছি
        return view('tenant.members.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ভিউ ফাইলটি আমরা পরের ধাপে তৈরি করছি
        return view('tenant.members.create');
    }

    /**
     * Store a new resource in storage.
     */
    public function store(Request $request)
    {
        // ডেটা ভ্যালিডেশন
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'join_date' => 'nullable|date',
        ]);

        // লগইন করা ইউজার (mess_admin) এর tenant_id নিন
        $tenantId = Auth::user()->tenant_id;

        // নতুন Member তৈরি করুন
        Member::create([
            'tenant_id' => $tenantId, // TenantScope স্বয়ংক্রিয়ভাবে এটি সেট করবে না, তাই ম্যানুয়ালি দিলাম
            'name' => $validatedData['name'],
            'phone' => $validatedData['phone'],
            'email' => $validatedData['email'],
            'join_date' => $validatedData['join_date'],
        ]);

        return redirect()->route('members.index')->with('success', 'Member added successfully.');
    }

    // ... (show, edit, update, destroy মেথডগুলো আমরা পরে যোগ করবো)
}