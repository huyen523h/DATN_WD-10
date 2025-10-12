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
        'short_description',
        'description',
        'location',
        'duration_days',
        'available_seats',
        'price',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'available_seats' => 'integer',
        'duration_days' => 'integer',
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

    /**
     * Get the main image URL for the tour.
     */
    public function getMainImageAttribute()
    {
        return $this->images()->where('is_cover', true)->first()?->image_url;
    }

    /**
     * Get formatted price.
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', '.') . ' VND';
    }

    /**
     * Get formatted departure date.
     */
    public function getFormattedDepartureDateAttribute()
    {
        return $this->departure_date ? $this->departure_date->format('d/m/Y') : null;
    }

    /**
     * Scope: Filter by location
     */
    public function scopeByLocation($query, $location)
    {
        return $query->where('location', 'like', '%' . $location . '%');
    }

    /**
     * Scope: Filter by price range
     */
    public function scopeByPriceRange($query, $minPrice = null, $maxPrice = null)
    {
        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }
        return $query;
    }

    /**
     * Scope: Filter available tours
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'active')
                    ->where('available_seats', '>', 0);
    }

    /**
     * Scope: Filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Active tours
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get wishlists for the tour.
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }
}
