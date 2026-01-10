<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuideAttendance extends Model
{
    protected $fillable = [
        'guide_id',
        'departure_id',
        'work_date',
        'status',
        'check_in_time',
        'check_out_time',
        'note',
        'base_salary',
        'bonus',
        'penalty',
        'total_salary',
    ];

    protected $casts = [
        'work_date' => 'date',
    ];

    public function guide()
    {
        return $this->belongsTo(User::class, 'guide_id');
    }

    public function departure()
    {
        return $this->belongsTo(TourDeparture::class, 'departure_id');
    }
}
