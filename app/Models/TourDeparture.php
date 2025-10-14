<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TourDeparture extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_id',
        'departure_date',
        'seats_total',
        'seats_available',
        'price',
        'child_price',
        'infant_price',
        'status', // string: available|contact|sold_out
    ];

    protected $casts = [
        'departure_date' => 'date',
        'price'         => 'decimal:2',
        'child_price'   => 'decimal:2',
        'infant_price'  => 'decimal:2',
    ];

    /**
     * Get the tour that owns the departure.
     */
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    /**
     * Get the bookings for the departure.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Check if departure is available.
     */
    public function isAvailable(): bool
    {
        return $this->seats_available > 0;
    }
}
