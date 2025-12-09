<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'type',
        'name',
        'description',
        'created_by',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'conversation_participants')
            ->withPivot(['joined_at', 'last_read_at', 'is_admin', 'muted'])
            ->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'desc');
    }

    public function latestMessage(): HasMany
    {
        return $this->hasMany(Message::class)->latest()->limit(1);
    }

    public function isGroupChat(): bool
    {
        return $this->type === 'group';
    }

    public function isPrivateChat(): bool
    {
        return $this->type === 'private';
    }

    public function getUnreadCount(User $user): int
    {
        $lastRead = $this->participants()
            ->where('user_id', $user->id)
            ->first()
            ?->pivot
                ?->last_read_at;

        if (!$lastRead) {
            return $this->messages()->count();
        }

        return $this->messages()
            ->where('created_at', '>', $lastRead)
            ->where('user_id', '!=', $user->id)
            ->count();
    }

    public function markAsRead(User $user): void
    {
        $this->participants()->updateExistingPivot($user->id, [
            'last_read_at' => now(),
        ]);
    }

    public function scopeForUser($query, User $user)
    {
        return $query->whereHas('participants', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });
    }

    public function scopeGroupChats($query)
    {
        return $query->where('type', 'group');
    }

    public function scopePrivateChats($query)
    {
        return $query->where('type', 'private');
    }
}
