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
        'start_time',
        'end_time',
        'seats_total',
        'seats_available',
        'price',
        'child_price',
        'infant_price',
        'meeting_point',
        'status', // string: available|contact|sold_out
        'status_notes',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'start_time'     => 'datetime:H:i',
        'end_time'       => 'datetime:H:i',
        'price'         => 'decimal:2',
        'child_price'   => 'decimal:2',
        'infant_price'  => 'decimal:2',
    ];

    /**
     * Get the tour that owns the departure.
     */
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class , 'tour_id');
    }

    /**
     * Get the bookings for the departure.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * The operations linked to this departure.
     */
    public function operations(): HasMany
    {
        return $this->hasMany(TourOperation::class, 'tour_departure_id');
    }

    /**
     * Check if departure is available.
     */
    public function isAvailable(): bool
    {
        return $this->seats_available > 0;
    }
}
