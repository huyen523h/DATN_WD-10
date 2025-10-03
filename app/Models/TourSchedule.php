<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_id',
        'day',
        'title',
        'description',
        'start_time',
        'end_time',
        'location',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'day' => 'integer',
            'sort_order' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the tour that owns the schedule.
     */
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }
}
