<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_id',
        'user_id',
        'rating',
        'comment',
        'images',
        'status',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    /**
     * Get the validation rules for rating.
     */
    public static function getRatingValidationRules(): array
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
        ];
    }

    /**
     * Get the tour that owns the review.
     */
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    /**
     * Get the user that owns the review.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if review is visible.
     */
    public function isVisible(): bool
    {
        return $this->status === 'visible';
    }

    /**
     * Get star rating display.
     */
    public function getStarRatingAttribute(): string
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }
}
