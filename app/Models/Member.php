<?php

namespace App\Models;
use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'reg_id',
        'phone',
        'email',
        'join_date',
        'is_active',
    ];

    /**
     * Get the tenant that the member belongs to.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the meals for the member.
     */
    public function meals()
    {
        return $this->hasMany(Meal::class);
    }

    /**
     * Get the deposits for the member.
     */
    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }
    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope);
    }
}