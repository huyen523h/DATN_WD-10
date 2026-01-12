<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // <--- Thêm dòng này

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_id',
        'user_id',
        'parent_id', // <--- THÊM DÒNG NÀY (Quan trọng để Admin lưu câu trả lời)
        'rating',
        'comment',
        'images',
        'status',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    /**
     * Quy tắc validate đánh giá
     */
    public static function getRatingValidationRules(): array
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
        ];
    }

    /**
     * Quan hệ: Review thuộc về Tour nào
     */
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    /**
     * Quan hệ: Review thuộc về User nào
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * [MỚI] Quan hệ: Lấy các câu trả lời (replies) của đánh giá này.
     * Khắc phục lỗi: Call to a member function isNotEmpty() on null
     */
    public function replies(): HasMany
    {
        // Một Review cha có nhiều Review con (dựa vào cột parent_id)
        return $this->hasMany(Review::class, 'parent_id');
    }

    /**
     * [MỚI] Quan hệ: Lấy đánh giá gốc (nếu đây là câu trả lời).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Review::class, 'parent_id');
    }

    /**
     * Kiểm tra xem review có được hiển thị không
     */
    public function isVisible(): bool
    {
        // Bạn có thể thêm 'approved' vào mảng này nếu muốn hiện cả review đã duyệt
        return in_array($this->status, ['visible', 'approved']);
    }

    /**
     * Hiển thị sao đánh giá
     */
    public function getStarRatingAttribute(): string
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }
}