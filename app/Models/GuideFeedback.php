<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuideFeedback extends Model
{
    protected $fillable = [
        'departure_id',
        'guide_id',
        'feedback_type',
        'subject',
        'content',
        'rating',
        'images',
        'supplier_name',
        'suggestions',
        'status',
        'admin_response',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'images' => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function departure(): BelongsTo
    {
        return $this->belongsTo(TourDeparture::class);
    }

    public function guide(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guide_id');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
