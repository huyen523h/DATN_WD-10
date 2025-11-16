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
        'duration',
        'duration_days',
        'duration_nights',

        'price',
        'original_price',
        'discount_price',

        'price_adult',
        'price_child',
        'price_infant',

        'includes',
        'excludes',
        'surcharges',
        'notes',
        'cancellation_policy',
        'visa_requirements',

        'available_seats',
        'availability_status',
        'status',
        'image',
        'departure_date',
        'avg_rating',
        'reviews_count',
    ];

    protected $casts = [
        'price'            => 'decimal:2',
        'original_price'   => 'decimal:2',
        'discount_price'   => 'decimal:2',
        'price_adult'      => 'decimal:2',
        'price_child'      => 'decimal:2',
        'price_infant'     => 'decimal:2',
        'departure_date'   => 'date',

        'avg_rating'       => 'float',
        'reviews_count'    => 'int',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(TourImage::class)
            ->orderByDesc('is_cover')
            ->orderBy('sort_order')
            ->orderByDesc('id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(TourSchedule::class)->orderBy('day_number');
    }

    public function departures(): HasMany
    {
        return $this->hasMany(TourDeparture::class, 'tour_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function publicReviews(): HasMany
    {
 
        return $this->hasMany(Review::class)
            ->where('status', 'approved') 
            ->whereNull('parent_id')      
            ->orderBy('created_at', 'desc');
    }

    public function wishlistedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlists')
            ->withTimestamps();
    }

    public function coverImage()
    {
        return $this->images()->where('is_cover', true)->first();
    }


    public function getMainImageAttribute()
    {
        return $this->images()->where('is_cover', true)->first()?->image_url;
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', '.') . ' VND';
    }

    public function getFormattedDepartureDateAttribute()
    {
        return $this->departure_date ? $this->departure_date->format('d/m/Y') : null;
    }

    public function scopeByLocation($query, $location)
    {
        return $query->where('location', 'like', '%' . $location . '%');
    }

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

    public function scopeAvailable($query)
    {
        return $query->where('status', 'active')
            ->where('available_seats', '>', 0);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }
}
