<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\HasMany;   
use App\Models\CheckIn;    

class BookingPassenger extends Model
{
    use HasFactory;

    protected $table = 'booking_passengers';

    protected $fillable = [
        'booking_id',
        'full_name',
        'gender',
        'birth_year',
        'phone',
        'email',
        'id_number',
        'id_type',
        'passenger_type',
        'payment_status',
        'amount_paid',
        'amount_total',
        'special_requests',
        'room_number',
        'room_type',
        'room_mate_id',
        'checked_in',
        'checked_in_at',
        'notes',
    ];

    /**
     * Passenger belongs to a booking
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function checkIns(): HasMany
    {
        return $this->hasMany(CheckIn::class, 'passenger_id');
    }
}
