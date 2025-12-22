<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\BelongsTo;

// class CheckIn extends Model
// {
//     protected $fillable = [
//         'departure_id',
//         'booking_id',
//         'checked_by',
//         'check_in_time',
//         'check_in_location',
//         'status',
//         'notes',
//     ];

//     protected $casts = [
//         'check_in_time' => 'datetime',
//     ];

//     public function departure(): BelongsTo
//     {
//         return $this->belongsTo(TourDeparture::class);
//     }

//     public function booking(): BelongsTo
//     {
//         return $this->belongsTo(Booking::class);
//     }

//     public function checkedBy(): BelongsTo
//     {
//         return $this->belongsTo(User::class, 'checked_by');
//     }
// }


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckIn extends Model
{
    protected $fillable = [
        'departure_id',
        'booking_id',
        'checked_by',
        'check_in_time',
        'check_in_location',
        'status',
        'notes',
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
    ];

    public function departure(): BelongsTo
    {
        return $this->belongsTo(TourDeparture::class, 'departure_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function checkedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_by');
    }
}
