<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Review; 
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;
    // --- THÊM CÁC HẰNG SỐ NÀY VÀO ---
    const STATUS_PENDING = 'pending';         // Chờ xác nhận (Trạng thái mặc định của bạn)
    const STATUS_CONFIRMED = 'confirmed';     // Đã xác nhận (Cho phép user thanh toán)
    const STATUS_PAID = 'paid';           // Đã thanh toán
    const STATUS_CANCELLED = 'cancelled';     // Đã hủy

    protected $fillable = [
        'user_id',
        'tour_id',
        'departure_id',
        'adults',
        'children',
        'infants',
        'total_amount',
        'status',
        'promotion_code',
        'note',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the user that owns the booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tour for the booking.
     */
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    /**
     * Get the departure for the booking.
     */
    public function departure(): BelongsTo
    {
        return $this->belongsTo(TourDeparture::class, 'departure_id');
    }

    /**
     * Get the promotion for the booking.
     */
    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    /**
     * Get the staff member who handled the booking.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    /**
     * Get the payments for the booking.
     */
    public function payment()
    {
        return $this->hasMany(Payment::class, 'booking_id', 'id');
    }

    /**
     * Get the invoice for the booking.
     */
    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    /**
     * Get the documents for the booking.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Get the chat for the booking.
     */
    public function chat(): HasOne
    {
        return $this->hasOne(Chat::class);
    }

    /**
     * Get total passengers.
     */
    public function getTotalPassengersAttribute(): int
    {
        return $this->adults + $this->children + $this->infants;
    }

    public function review(): HasOne
    {
        // Một Booking chỉ có 1 Review
        return $this->hasOne(Review::class);
    }

    public function isCompleted(): bool
    {
        if ($this->status !== 'paid') {
            return false;
        }

        $this->loadMissing(['tour', 'departure']);

        if (!$this->tour || !$this->departure) {
            return false; // Thiếu dữ liệu, không thể tính
        }

        try {
            // Tính ngày kết thúc
            $ngay_khoi_hanh = Carbon::parse($this->departure->departure_date);
            $so_ngay = (int) $this->tour->duration_days;
            
            // Công thức: Ngày khởi hành + Số ngày - 1
            $ngay_ket_thuc = $ngay_khoi_hanh->addDays($so_ngay - 1);

            // So sánh với ngày hôm nay
            return $ngay_ket_thuc < Carbon::today();

        } catch (\Exception $e) {
            // Lỗi khi parse ngày (ví dụ: dữ liệu bị null)
            return false;
        }

        // test không xét dk ngày để hoàn thành tour -----------------------------------------
        // Điều kiện 1: Phải "Đã thanh toán"
        // if ($this->status !== 'paid') {
        //     return false;
        // }

        // // Điều kiện 2: Logic thời gian (BỊ TẮT ĐỂ TEST)
        // // Chúng ta mặc định trả về "True" nếu đã thanh toán
        // return true;
    }
}
