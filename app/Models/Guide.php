<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guide extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', // legacy column to keep DB schema compatible
        'code',
        'full_name',
        'date_of_birth',
        'gender',
        'avatar_url',
        'phone',
        'email',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone',
        'primary_language',
        'experience_years',
        'specialty_routes',
        'biography',
        'certifications',
        'rating_average',
        'rating_count',
        'health_status',
        'last_medical_check_at',
        'status',
        'metadata',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'certifications' => 'array',
        'metadata' => 'array',
        'last_medical_check_at' => 'datetime',
        'rating_average' => 'decimal:2',
        'rating_count' => 'integer',
        'experience_years' => 'integer',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(GuideCategory::class, 'guide_guide_category')
            ->withTimestamps();
    }

    public function languages(): HasMany
    {
        return $this->hasMany(GuideLanguage::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(GuideDocument::class);
    }

    public function healthRecords(): HasMany
    {
        return $this->hasMany(GuideHealthRecord::class);
    }

    public function staffAssignments(): HasMany
    {
        return $this->hasMany(OperationStaffAssignment::class);
    }
}


