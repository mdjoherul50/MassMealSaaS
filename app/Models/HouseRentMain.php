<?php

namespace App\Models;

use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseRentMain extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'month',
        'house_rent',
        'wifi_bill',
        'current_bill',
        'gas_bill',
        'extra_bill',
        'extra_note',
        'total',
        'assigned_to_members',
        'remaining_balance',
        'carry_forward',
        'status',
        'created_by',
    ];

    protected $casts = [
        'house_rent' => 'decimal:2',
        'wifi_bill' => 'decimal:2',
        'current_bill' => 'decimal:2',
        'gas_bill' => 'decimal:2',
        'extra_bill' => 'decimal:2',
        'total' => 'decimal:2',
        'assigned_to_members' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
        'carry_forward' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope);

        static::saving(function ($model) {
            $model->total = $model->house_rent + $model->wifi_bill + $model->current_bill + $model->gas_bill + $model->extra_bill;
            $model->remaining_balance = $model->total - $model->assigned_to_members;
        });
    }

    /**
     * Get the user who created this record.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get related member house rents for this month.
     */
    public function memberRents()
    {
        return $this->hasMany(HouseRent::class, 'month', 'month');
    }
}
