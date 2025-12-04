<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuideHealthRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'guide_id',
        'check_date',
        'status',
        'doctor_name',
        'hospital',
        'notes',
        'attachments',
    ];

    protected $casts = [
        'check_date' => 'date',
        'attachments' => 'array',
    ];

    public function guide(): BelongsTo
    {
        return $this->belongsTo(Guide::class);
    }
}


