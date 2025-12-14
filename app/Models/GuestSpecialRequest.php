<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuestSpecialRequest extends Model
{
    protected $fillable = [
        'booking_id',
        'departure_id',
        'request_type',
        'title',
        'description',
        'status',
        'notes',
        'updated_by',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function departure(): BelongsTo
    {
        return $this->belongsTo(TourDeparture::class);
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
