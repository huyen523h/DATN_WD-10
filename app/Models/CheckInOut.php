<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class CheckInOut extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'booking_id',
        'type',
        'check_time',
        'location',
        'latitude',
        'longitude',
        'notes',
        'status',
        'verified_by',
        'verified_at',
        'metadata'
    ];

    protected $casts = [
        'check_time' => 'datetime',
        'verified_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'metadata' => 'array'
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    // Scopes
    public function scopeCheckIn($query)
    {
        return $query->where('type', 'check_in');
    }

    public function scopeCheckOut($query)
    {
        return $query->where('type', 'check_out');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('check_time', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('check_time', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('check_time', now()->month)
                    ->whereYear('check_time', now()->year);
    }

    // Accessors & Mutators
    public function getFormattedCheckTimeAttribute()
    {
        return $this->check_time->format('d/m/Y H:i:s');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'confirmed' => 'success',
            'cancelled' => 'danger'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getTypeLabelAttribute()
    {
        return $this->type === 'check_in' ? 'Check-in' : 'Check-out';
    }

    public function getLocationAttribute($value)
    {
        return $value ?: 'Không xác định';
    }

    // Methods
    public function isCheckIn(): bool
    {
        return $this->type === 'check_in';
    }

    public function isCheckOut(): bool
    {
        return $this->type === 'check_out';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function confirm(string $verifiedBy = null): bool
    {
        return $this->update([
            'status' => 'confirmed',
            'verified_by' => $verifiedBy,
            'verified_at' => now()
        ]);
    }

    public function cancel(): bool
    {
        return $this->update([
            'status' => 'cancelled'
        ]);
    }

    public function getDistanceFromLocation($lat, $lng): float
    {
        if (!$this->latitude || !$this->longitude) {
            return 0;
        }

        $earthRadius = 6371; // km

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($lat);
        $lonTo = deg2rad($lng);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos($latFrom) * cos($latTo) *
             sin($lonDelta / 2) * sin($lonDelta / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
