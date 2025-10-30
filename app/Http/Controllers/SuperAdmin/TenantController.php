<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant; // Tenant মডেল ইম্পোর্ট করুন
use Illuminate\Http\Request;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // এখানে TenantScope কাজ করবে না, কারণ আমরা Super Admin
        // সব টেন্যান্টকে লোড করুন
        $tenants = Tenant::with('owner')->latest()->paginate(20);
        
        return view('superadmin.tenants.index', compact('tenants'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant)
    {
        // একটি নির্দিষ্ট টেন্যান্টের বিস্তারিত দেখান
        $tenant->load('users', 'members'); // User ও Member লোড করুন
        return view('superadmin.tenants.show', compact('tenant'));
    }

    /**
     * Update the specified resource in storage (e.g., suspend/activate).
     */
    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,suspended',
        ]);

        $tenant->update($validated);
        return redirect()->route('superadmin.tenants.show', $tenant)->with('success', 'Tenant status updated.');
    }

    // ... (create, store, edit, destroy মেথডগুলো আমরা আপাতত বাদ রাখছি)
}