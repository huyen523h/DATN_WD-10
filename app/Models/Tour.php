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

    protected $casts = [
        'departure_date' => 'date',
        'price' => 'decimal:2',
    ];

    protected $appends = [
        'formatted_price',
        'image_url',
        'formatted_departure_date',
        'status'
    ];

    // Relation với Category nếu bảng categories tồn tại
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Accessor để format giá tiền
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', '.') . ' VNĐ';
    }

    // Accessor để lấy URL hình ảnh đầy đủ
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/tour-default.jpg'); // Default image
    }

    // Accessor để format ngày khởi hành
    public function getFormattedDepartureDateAttribute()
    {
        if ($this->departure_date) {
            return $this->departure_date->format('d/m/Y');
        }
        return null;
    }

    // Accessor để lấy trạng thái tour
    public function getStatusAttribute()
    {
        if (!$this->departure_date) {
            return 'Chưa có lịch';
        }
        
        if ($this->departure_date->isPast()) {
            return 'Đã kết thúc';
        }
        
        if ($this->available_seats <= 0) {
            return 'Hết chỗ';
        }
        
        return 'Còn chỗ';
    }

    // Scope để lấy tours còn chỗ
    public function scopeAvailable($query)
    {
        return $query->where('available_seats', '>', 0);
    }

    // Scope để lấy tours theo location
    public function scopeByLocation($query, $location)
    {
        return $query->where('location', 'like', '%' . $location . '%');
    }

    // Scope để lấy tours theo khoảng giá
    public function scopeByPriceRange($query, $minPrice = null, $maxPrice = null)
    {
        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }
        return $query;
    }
}