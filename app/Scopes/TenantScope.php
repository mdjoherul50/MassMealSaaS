<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // যখন ইউজার লগইন করা থাকবে এবং সে super_admin না হবে
        if (Auth::check() && Auth::user()->role !== 'super_admin') {
            $tenantId = Auth::user()->tenant_id;
            
            if ($tenantId) {
                // স্বয়ংক্রিয়ভাবে tenant_id দিয়ে ফিল্টার করো
                $builder->where($model->getTable() . '.tenant_id', $tenantId);
            }
        }
    }
}