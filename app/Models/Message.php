<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'message',
        'attachment_path',
        'attachment_type',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    protected $appends = ['time_ago'];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function readBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'message_reads')
            ->withPivot('read_at')
            ->withTimestamps();
    }

    public function markAsRead(User $user): void
    {
        if (!$this->readBy()->where('user_id', $user->id)->exists()) {
            $this->readBy()->attach($user->id, ['read_at' => now()]);
        }
    }

    public function isReadBy(User $user): bool
    {
        return $this->readBy()->where('user_id', $user->id)->exists();
    }

    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    public function hasAttachment(): bool
    {
        return !empty($this->attachment_path);
    }

    public function isImage(): bool
    {
        return $this->attachment_type === 'image';
    }

    public function isFile(): bool
    {
        return $this->attachment_type === 'file';
    }
}
