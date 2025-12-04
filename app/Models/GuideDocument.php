<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuideDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'guide_id',
        'type',
        'name',
        'file_path',
        'issued_by',
        'issued_at',
        'expires_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'issued_at' => 'date',
        'expires_at' => 'date',
    ];

    public function guide(): BelongsTo
    {
        return $this->belongsTo(Guide::class);
    }
}


