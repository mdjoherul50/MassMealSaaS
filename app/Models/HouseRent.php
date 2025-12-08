<?php

namespace App\Models;

use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HouseRentMain;

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
        'status',
        'created_by',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
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