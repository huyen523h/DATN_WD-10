<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_url',
        'link_url',
        'type',
        'position',
        'sort_order',
        'is_active',
        'start_date',
        'end_date',
        'target_audience',
        'click_count',
        'view_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'target_audience' => 'array',
        'click_count' => 'integer',
        'view_count' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Scope: Active banners
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Banners by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: Banners by position
     */
    public function scopeByPosition($query, $position)
    {
        return $query->where('position', $position);
    }

    /**
     * Scope: Currently active banners (within date range)
     */
    public function scopeCurrentlyActive($query)
    {
        $now = now();
        return $query->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', $now);
            });
    }

    /**
     * Scope: Ordered by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Check if banner is currently active
     */
    public function isCurrentlyActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($this->start_date && $this->start_date->gt($now)) {
            return false;
        }

        if ($this->end_date && $this->end_date->lt($now)) {
            return false;
        }

        return true;
    }

    /**
     * Increment view count
     */
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    /**
     * Increment click count
     */
    public function incrementClickCount(): void
    {
        $this->increment('click_count');
    }

    /**
     * Get formatted date range
     */
    public function getDateRangeAttribute(): string
    {
        if (!$this->start_date && !$this->end_date) {
            return 'Không giới hạn';
        }

        $start = $this->start_date ? $this->start_date->format('d/m/Y') : 'Không giới hạn';
        $end = $this->end_date ? $this->end_date->format('d/m/Y') : 'Không giới hạn';

        return "{$start} - {$end}";
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute(): string
    {
        if (!$this->is_active) {
            return 'Không hoạt động';
        }

        if (!$this->isCurrentlyActive()) {
            return 'Hết hạn';
        }

        return 'Đang hoạt động';
    }

    /**
     * Get type text
     */
    public function getTypeTextAttribute(): string
    {
        return match ($this->type) {
            'hero' => 'Hero Banner',
            'promotion' => 'Khuyến mãi',
            'category' => 'Danh mục',
            'featured' => 'Nổi bật',
            default => 'Khác'
        };
    }

    /**
     * Get position text
     */
    public function getPositionTextAttribute(): string
    {
        return match ($this->position) {
            'top' => 'Đầu trang',
            'middle' => 'Giữa trang',
            'bottom' => 'Cuối trang',
            'sidebar' => 'Thanh bên',
            default => 'Khác'
        };
    }
}
