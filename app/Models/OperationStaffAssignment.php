<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OperationStaffAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_operation_id',
        'guide_id',
        'user_id',
        'external_name',
        'role',
        'assignment_type',
        'status',
        'confirmed_at',
        'declined_at',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'declined_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function operation(): BelongsTo
    {
        return $this->belongsTo(TourOperation::class, 'tour_operation_id');
    }

    public function guide(): BelongsTo
    {
        return $this->belongsTo(Guide::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}


