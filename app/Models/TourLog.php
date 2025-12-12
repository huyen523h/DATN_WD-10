<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourLog extends Model
{
    protected $fillable = [
        'departure_id',
        'guide_id',
        'log_date',
        'type',
        'content',
        'images',
        'status',
    ];

    protected $casts = [
        'log_date' => 'date',
        'images' => 'array',
    ];

    public function departure(): BelongsTo
    {
        return $this->belongsTo(TourDeparture::class);
    }

    public function guide(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guide_id');
    }
}
