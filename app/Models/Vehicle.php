<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'license_plate',
        'vehicle_type',   // loại xe: 4 chỗ, 7 chỗ, bus...
        'brand',          // hãng xe: Toyota, Ford...
        'year',           // năm sản xuất
        'color',          // màu xe
        'status',         // available, maintenance, inactive
        'driver_name',
        'driver_phone',
        'notes',
    ];

    protected $casts = [
        'year' => 'integer',
        'status' => 'integer', // Cast status thành integer
    ];

    /**
     * Mutator: Đảm bảo status luôn được lưu dạng integer
     */
    public function setStatusAttribute($value)
    {
        // Nếu là string số, chuyển sang integer
        if (is_string($value) && is_numeric($value)) {
            $this->attributes['status'] = (int)$value;
        }
        // Nếu là string text, chuyển đổi sang integer
        elseif (is_string($value)) {
            $statusMap = [
                'available' => 1,
                'maintenance' => 2,
                'inactive' => 0,
            ];
            $this->attributes['status'] = $statusMap[$value] ?? 0;
        }
        // Nếu đã là integer, lưu trực tiếp
        else {
            $this->attributes['status'] = (int)$value;
        }
    }

    /**
     * Accessor: Chuyển đổi status từ string sang integer nếu cần (cho dữ liệu cũ)
     */
    public function getStatusAttribute($value)
    {
        // Nếu là string text, chuyển đổi sang integer
        if (is_string($value) && !is_numeric($value)) {
            $statusMap = [
                'available' => 1,
                'maintenance' => 2,
                'inactive' => 0,
            ];
            return $statusMap[$value] ?? 0;
        }
        // Nếu là string số hoặc integer, trả về integer
        return (int)$value;
    }

    /**
     * Scope: chỉ xe đang hoạt động / sẵn sàng.
     */
    public function scopeAvailable($query)
    {
        return $query->where(function($q) {
            $q->where('status', 1)
              ->orWhere('status', 'available');
        });
    }
}


