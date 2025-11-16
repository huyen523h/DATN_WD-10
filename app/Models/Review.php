<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- THÊM MỚI

class Review extends Model
{
    use HasFactory;


    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_HIDDEN = 'hidden';

    protected $fillable = [
        'tour_id',
        'user_id',
        'booking_id', 
        'parent_id',  
        'rating',
        'comment',
        'images',
        'status',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public static function getRatingValidationRules(): array
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
        ];
    }

 
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

   
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

   
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Review::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        // Một review gốc (parent) có thể có nhiều trả lời (replies)
        return $this->hasMany(Review::class, 'parent_id')->orderBy('created_at', 'asc');
    }


    public function isVisible(): bool
    {
        return $this->status === self::STATUS_APPROVED; // Đổi từ 'visible'
    }

    public function getStarRatingAttribute(): string
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }
}