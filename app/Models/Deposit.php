<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'member_id',
        'amount',
        'method',
        'reference',
        'date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the member that the deposit belongs to.
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}