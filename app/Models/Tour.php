<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;

    protected $table = 'tours';

    protected $fillable = [
        'category_id',
        'title', // Tên
        'short_description',
        'description', // Lịch trình
        'price', // Giá
        'location', // Địa điểm
        'duration',
        'available_seats',
        'departure_date', // Ngày khởi hành
        'image', // Hình ảnh (path)
    ];
      // 👇 Thêm dòng này
    protected $casts = [
        'departure_date' => 'date',
    ];


    // Relation với Category nếu bảng categories tồn tại
    public function category()
    {
        return $this->belongsTo(category::class);
    }
}