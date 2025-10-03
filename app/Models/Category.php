<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the tours for the category.
     */
    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class);
    }

    /**
     * Get tours count for the category.
     */
    public function getToursCountAttribute()
    {
        return $this->tours()->count();
    }
}
