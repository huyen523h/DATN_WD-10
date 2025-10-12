<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'discount_percent',
        'discount_amount',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'discount_percent' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the bookings for the promotion.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Check if promotion is active.
     */
    public function isActive(): bool
    {
        $now = Carbon::now();
        return $this->status === 'active' 
            && $this->start_date <= $now 
            && $this->end_date >= $now;
    }

    /**
     * Calculate discount amount.
     */
    public function calculateDiscount($amount): float
    {
        if ($this->discount_percent) {
            return $amount * ($this->discount_percent / 100);
        }
        
        return $this->discount_amount ?? 0;
    }
}
