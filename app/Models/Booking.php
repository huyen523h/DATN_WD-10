<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tour_id',
        'departure_id',
        'promotion_id',
        'staff_id',
        'booking_date',
        'adults',
        'children',
        'infants',
        'total_amount',
        'status',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'datetime',
            'adults' => 'integer',
            'children' => 'integer',
            'infants' => 'integer',
            'total_amount' => 'decimal:2',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tour that owns the booking.
     */
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    /**
     * Get the departure that owns the booking.
     */
    public function departure(): BelongsTo
    {
        return $this->belongsTo(TourDeparture::class);
    }

    /**
     * Get the staff member assigned to the booking.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to filter by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('booking_date', [$startDate, $endDate]);
    }

    /**
     * Get total passengers count.
     */
    public function getTotalPassengersAttribute()
    {
        return ($this->adults ?? 0) + ($this->children ?? 0) + ($this->infants ?? 0);
    }
}
