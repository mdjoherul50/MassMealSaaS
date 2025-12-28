<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo_path',
        'owner_user_id',
        'plan',
        'plan_id',
        'plan_started_at',
        'plan_expires_at',
        'phone',
        'address',
        'status',
        'subscription_status',
    ];

    protected $casts = [
        'plan_started_at' => 'date',
        'plan_expires_at' => 'date',
    ];

    /**
     * Get the users associated with the tenant.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the members associated with the tenant.
     */
    public function members()
    {
        return $this->hasMany(Member::class);
    }

    /**
     * Get the owner of the tenant.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    /**
     * Get the plan that the tenant is subscribed to.
     */
    public function planDetails()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    /**
     * Check if tenant subscription is active.
     */
    public function isSubscriptionActive(): bool
    {
        if ($this->subscription_status === 'active') {
            return true;
        }

        if ($this->subscription_status === 'trial' && $this->plan_expires_at && $this->plan_expires_at->gt(now())) {
            return true;
        }

        return false;
    }

    /**
     * Check if tenant is on trial.
     */
    public function isOnTrial(): bool
    {
        return $this->subscription_status === 'trial' && $this->plan_expires_at && $this->plan_expires_at->gt(now());
    }

    /**
     * Get remaining trial days.
     */
    public function remainingTrialDays(): int
    {
        if (!$this->isOnTrial()) {
            return 0;
        }

        return now()->diffInDays($this->plan_expires_at, false);
    }
}