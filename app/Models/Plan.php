<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price_monthly',
        'price_yearly',
        'max_members',
        'max_storage_mb',
        'features',
        'trial_days',
        'is_active',
        'is_popular',
        'sort_order',
    ];

    protected $casts = [
        'price_monthly' => 'decimal:2',
        'price_yearly' => 'decimal:2',
        'max_members' => 'integer',
        'max_storage_mb' => 'integer',
        'features' => 'array',
        'trial_days' => 'integer',
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function tenants()
    {
        return $this->hasMany(Tenant::class, 'plan_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
