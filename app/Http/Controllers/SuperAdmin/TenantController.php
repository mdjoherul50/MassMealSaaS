<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Tenant::with(['owner', 'planDetails']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('subscription_status')) {
            $query->where('subscription_status', $request->subscription_status);
        }

        $tenants = $query->latest()->paginate(20);

        return view('superadmin.tenants.index', compact('tenants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $plans = Plan::active()->ordered()->get();
        return view('superadmin.tenants.create', compact('plans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'plan_id' => 'required|exists:plans,id',
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|unique:users,email',
            'owner_password' => 'required|string|min:8',
        ]);

        DB::beginTransaction();
        try {
            $plan = Plan::findOrFail($validated['plan_id']);

            $tenant = Tenant::create([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'plan_id' => $validated['plan_id'],
                'plan_started_at' => now(),
                'plan_expires_at' => now()->addDays($plan->trial_days),
                'status' => 'active',
                'subscription_status' => 'trial',
            ]);

            $owner = User::create([
                'name' => $validated['owner_name'],
                'email' => $validated['owner_email'],
                'password' => Hash::make($validated['owner_password']),
                'tenant_id' => $tenant->id,
                'role_id' => 2,
            ]);

            $tenant->update(['owner_user_id' => $owner->id]);

            DB::commit();

            return redirect()->route('superadmin.tenants.index')
                ->with('success', __('tenant.tenant_created'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create tenant: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant)
    {
        $tenant->load(['users', 'members', 'planDetails', 'owner']);
        return view('superadmin.tenants.show', compact('tenant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenant $tenant)
    {
        $plans = Plan::active()->ordered()->get();
        return view('superadmin.tenants.edit', compact('tenant', 'plans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'plan_id' => 'required|exists:plans,id',
            'status' => 'required|in:active,suspended',
            'subscription_status' => 'required|in:trial,active,expired,cancelled',
            'plan_expires_at' => 'nullable|date',
        ]);

        $tenant->update($validated);

        return redirect()->route('superadmin.tenants.show', $tenant)
            ->with('success', __('tenant.tenant_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        if ($tenant->users()->count() > 1 || $tenant->members()->count() > 0) {
            return redirect()->route('superadmin.tenants.index')
                ->with('error', 'Cannot delete tenant with active users or members');
        }

        $tenant->delete();

        return redirect()->route('superadmin.tenants.index')
            ->with('success', __('tenant.tenant_deleted'));
    }

    /**
     * Change tenant plan.
     */
    public function changePlan(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'subscription_status' => 'required|in:trial,active',
        ]);

        $plan = Plan::findOrFail($validated['plan_id']);

        $tenant->update([
            'plan_id' => $validated['plan_id'],
            'subscription_status' => $validated['subscription_status'],
            'plan_started_at' => now(),
            'plan_expires_at' => $validated['subscription_status'] === 'trial'
                ? now()->addDays($plan->trial_days)
                : now()->addYear(),
        ]);

        return redirect()->route('superadmin.tenants.show', $tenant)
            ->with('success', 'Plan changed successfully');
    }
}