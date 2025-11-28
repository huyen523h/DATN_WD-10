<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuideLanguage extends Model
{
    use HasFactory;

    protected $fillable = [
        'guide_id',
        'language',
        'proficiency',
        'certification_code',
        'certified_at',
    ];

    protected $casts = [
        'certified_at' => 'date',
    ];

    public function guide(): BelongsTo
    {
        return $this->belongsTo(Guide::class);
    }
}


