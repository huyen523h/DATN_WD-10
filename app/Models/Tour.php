<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tour extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'short_description',
        'description',
        'price',
        'location',
        'duration',
        'available_seats',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

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
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope a query to filter by price range.
     */
    public function scopeByPriceRange($query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('price', [$minPrice, $maxPrice]);
    }

    /**
     * Scope a query to filter by location.
     */
    public function scopeByLocation($query, $location)
    {
        return $query->where('location', 'like', "%{$location}%");
    }

    /**
     * Scope a query to search tours.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('short_description', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%");
        });
    }

    /**
     * Scope a query to sort by price.
     */
    public function scopeSortByPrice($query, $direction = 'asc')
    {
        return $query->orderBy('price', $direction);
    }

    /**
     * Scope a query to sort by created date.
     */
    public function scopeSortByDate($query, $direction = 'desc')
    {
        return $query->orderBy('created_at', $direction);
    }

    /**
     * Get the average rating for the tour.
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Get the total reviews count for the tour.
     */
    public function getReviewsCountAttribute()
    {
        return $this->reviews()->count();
    }

    /**
     * Get the main image for the tour.
     */
    public function getMainImageAttribute()
    {
        return $this->images()->where('is_main', true)->first()?->image_url;
    }
}
