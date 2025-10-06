<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tour extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'duration_days',
        'price',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Get the category that owns the tour.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the images for the tour.
     */
    public function images(): HasMany
    {
        return $this->hasMany(TourImage::class);
    }

    /**
     * Get the schedules for the tour.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(TourSchedule::class);
    }

    /**
     * Get the departures for the tour.
     */
    public function departures(): HasMany
    {
        return $this->hasMany(TourDeparture::class);
    }

    /**
     * Get the bookings for the tour.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the reviews for the tour.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * The users that have this tour in their wishlist.
     */
    public function wishlistedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlists')
                    ->withTimestamps();
    }

    /**
     * Get the cover image for the tour.
     */
    public function coverImage()
    {
        return $this->images()->where('is_cover', true)->first();
    }
}
