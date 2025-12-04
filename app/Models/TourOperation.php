<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class TourOperation extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_id',
        'tour_departure_id',
        'operation_code',
        'start_datetime',
        'end_datetime',
        'meeting_point',
        'status',
        'notes',
        'itinerary_snapshot',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'itinerary_snapshot' => 'array',
    ];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function departure(): BelongsTo
    {
        return $this->belongsTo(TourDeparture::class, 'tour_departure_id');
    }

    public function staffAssignments(): HasMany
    {
        return $this->hasMany(OperationStaffAssignment::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(OperationService::class);
    }

    public function guides(): HasManyThrough
    {
        return $this->hasManyThrough(
            Guide::class,
            OperationStaffAssignment::class,
            'tour_operation_id',
            'id',
            'id',
            'guide_id'
        );
    }
}


