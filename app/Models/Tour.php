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
        'duration',       // dạng text "3 ngày 2 đêm"
        'duration_days',  // số ngày (mới)
        'nights',         // số đêm (mới)

        'price',
        'original_price',
        'discount_price', // mới

        'price_adult',
        'price_child',
        'price_infant',

        'includes',
        'excludes',
        'surcharges',         // mới
        'notes',
        'cancellation_policy',
        'visa_requirements',  // mới

        'available_seats',
        'availability_status', // available|contact|sold_out (tổng thể)
        'image',
        'departure_date', // nếu bạn đang dùng ở chỗ khác
    ];

    protected $casts = [
        'price'          => 'decimal:2',
        'original_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'price_adult'    => 'decimal:2',
        'price_child'    => 'decimal:2',
        'price_infant'   => 'decimal:2',
        'departure_date' => 'date',
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
        return $this->hasMany(TourImage::class)
            ->orderByDesc('is_cover')   // CHANGED
            ->orderBy('sort_order')     // CHANGED
            ->orderByDesc('id');        // CHANGED (ảnh mới nhất nếu chưa có sort)
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
