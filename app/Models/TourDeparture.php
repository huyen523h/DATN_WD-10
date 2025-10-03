<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourDeparture extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_id',
        'departure_date',
        'return_date',
        'available_seats',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'departure_date' => 'date',
            'return_date' => 'date',
            'available_seats' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the tour that owns the departure.
     */
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('departure_date', [$startDate, $endDate]);
    }

    /**
     * Scope a query to get available departures.
     */
    public function scopeAvailable($query)
    {
        return $query->where('available_seats', '>', 0)
            ->where('status', 'active')
            ->where('departure_date', '>=', now());
    }
}
