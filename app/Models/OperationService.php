<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OperationService extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_operation_id',
        'service_type',
        'provider_name',
        'contact_person',
        'contact_phone',
        'contact_email',
        'booking_code',
        'quantity',
        'cost',
        'status',
        'confirmation_deadline',
        'confirmed_at',
        'requirements',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'confirmation_deadline' => 'datetime',
        'confirmed_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function operation(): BelongsTo
    {
        return $this->belongsTo(TourOperation::class, 'tour_operation_id');
    }
}


