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
        'departure_id', // Liên kết với lịch khởi hành cụ thể
        'guide_id', // Hướng dẫn viên phụ trách
        'day_number',
        'title',
        'description', // Mô tả ngày
        'location', // Địa điểm
        'start_time', // Giờ khởi hành
        'end_time',
        'meeting_point',
        'activities',
        'meals',
        'accommodation',
        'transportation',
        'notes',
        'images',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'images' => 'array',
    ];

    /**
     * Get the tour that owns the schedule.
     */
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    /**
     * Get the departure associated with this schedule.
     */
    public function departure(): BelongsTo
    {
        return $this->belongsTo(TourDeparture::class, 'departure_id');
    }

    /**
     * Get the guide assigned to this schedule day.
     */
    public function guide(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guide_id');
    }
}
