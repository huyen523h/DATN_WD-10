<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'status',
        'related_type',
        'related_id',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    /**
     * Get the user that owns the notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if notification is unread.
     */
    public function isUnread(): bool
    {
        return $this->status === 'unread';
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(): void
    {
        $this->update(['status' => 'read']);
    }
}
