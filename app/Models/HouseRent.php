<?php

namespace App\Models;

use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HouseRentMain;
use App\Models\Member;
use App\Models\User;
use App\Models\Tenant;

class HouseRent extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'member_id',
        'month',
        'house_rent',
        'wifi_bill',
        'current_bill',
        'gas_bill',
        'extra_bill',
        'extra_note',
        'total',
        'paid_amount',
        'due_amount',
        'status',
        'payment_method',
        'payment_date',
        'created_by',
    ];

    protected $casts = [
        'house_rent' => 'decimal:2',
        'wifi_bill' => 'decimal:2',
        'current_bill' => 'decimal:2',
        'gas_bill' => 'decimal:2',
        'extra_bill' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function getPaymentStatusAttribute()
    {
        if ($this->paid_amount >= $this->total) {
            return 'paid';
        } elseif ($this->paid_amount > 0) {
            return 'partial';
        }
        return 'unpaid';
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope);

        $recalc = function (self $rent): void {
            if (!$rent->month) {
                return;
            }

            $main = HouseRentMain::where('month', $rent->month)->first();
            if (!$main) {
                return;
            }

            $assigned = self::where('month', $rent->month)->sum('total');
            $main->assigned_to_members = $assigned;
            $main->save();
        };

        static::created($recalc);
        static::updated($recalc);
        static::deleted($recalc);
    }
}