<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_id',
        'image_url',
        'is_main',
        'alt_text',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_main' => 'boolean',
            'sort_order' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the tour that owns the image.
     */
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }
}
