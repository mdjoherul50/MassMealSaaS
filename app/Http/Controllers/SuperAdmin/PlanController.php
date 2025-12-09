<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::ordered()->paginate(20);
        return view('superadmin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('superadmin.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:plans,slug',
            'description' => 'nullable|string',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'max_members' => 'required|integer|min:1',
            'max_storage_mb' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'trial_days' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_popular' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_popular'] = $request->has('is_popular');

        Plan::create($validated);

        return redirect()->route('superadmin.plans.index')
            ->with('success', __('plan.plan_created'));
    }

    public function show(Plan $plan)
    {
        $plan->load('tenants');
        return view('superadmin.plans.show', compact('plan'));
    }

    public function edit(Plan $plan)
    {
        return view('superadmin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:plans,slug,' . $plan->id,
            'description' => 'nullable|string',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'max_members' => 'required|integer|min:1',
            'max_storage_mb' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'trial_days' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_popular' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_popular'] = $request->has('is_popular');

        $plan->update($validated);

        return redirect()->route('superadmin.plans.index')
            ->with('success', __('plan.plan_updated'));
    }

    public function destroy(Plan $plan)
    {
        if ($plan->tenants()->count() > 0) {
            return redirect()->route('superadmin.plans.index')
                ->with('error', 'Cannot delete plan with active tenants');
        }

        $plan->delete();

        return redirect()->route('superadmin.plans.index')
            ->with('success', __('plan.plan_deleted'));
    }
}
