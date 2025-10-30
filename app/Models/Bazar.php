<?php

namespace App\Models;
use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bazar extends Model
{
    use HasFactory;

    // 'bazars' টেবিলের জন্য
    protected $table = 'bazars'; 

    protected $fillable = [
        'tenant_id',
        'date',
        'buyer_id',
        'description',
        'total_amount',
        'items',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'items' => 'array',
        'date' => 'date',
    ];

    /**
     * Get the user who bought the items.
     */
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
    protected static function booted(): void
{
    static::addGlobalScope(new TenantScope);
}

}