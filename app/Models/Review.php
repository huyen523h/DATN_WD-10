<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tour_id',
        'rating',
        'comment',
        'images', // Đã có cho path hình ảnh
        'status', // Trạng thái (ví dụ: 'active', 'pending')
    ];

    // Tắt timestamps vì DB chỉ có created_at, không có updated_at
    public $timestamps = false;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => 'string', // Cast status thành string nếu dùng enum đơn giản
        ];
    }

    // Quan hệ: Review belongsTo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ: Review belongsTo Tour
    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }
}