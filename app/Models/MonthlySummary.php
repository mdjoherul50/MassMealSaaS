<?php

namespace App\Models;
use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlySummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'month',
        'total_meal',
        'total_bazar',
        'avg_meal_rate',
    ];

    protected static function booted(): void
{
    static::addGlobalScope(new TenantScope);
}
}