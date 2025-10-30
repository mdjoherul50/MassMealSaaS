<?php

namespace App\Models;
use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'member_id',
        'date',
        'breakfast', // meal_count এর বদলে
        'lunch',     // meal_count এর বদলে
        'dinner',    // meal_count এর বদলে
        'created_by',
    ];

    /**
     * Get the member that the meal belongs to.
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the user who created the meal entry.
     */
    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    protected static function booted(): void
{
    static::addGlobalScope(new TenantScope);
}
}