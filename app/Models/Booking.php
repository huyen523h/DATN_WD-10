<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;
use App\Models\BookingPassenger;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tour_id',
        'departure_id',
        'adults',
        'children',
        'infants',
        'additional_services',
        'additional_services_total',
        'total_amount',
        'paid_amount',
        'payment_method',
        'status',
        'source',
        'booking_source', // Nguồn booking: website, zalo, facebook, phone
        'sale_staff_id', // Sale phụ trách booking
        'promotion_code',
        'note',
        'passenger_manifest_file',
        'expires_at',
        'cancel_reason',
        'receipt_image',
        'contract_file',
        'service_details',
    ];

    /**
     * Booking source constants
     */
    const SOURCE_WEBSITE = 'website';
    const SOURCE_ZALO = 'zalo';
    const SOURCE_FACEBOOK = 'facebook';
    const SOURCE_PHONE = 'phone';

    const BOOKING_SOURCES = [
        self::SOURCE_WEBSITE => 'Website',
        self::SOURCE_ZALO => 'Zalo',
        self::SOURCE_FACEBOOK => 'Facebook',
        self::SOURCE_PHONE => 'Điện thoại',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'expires_at' => 'datetime',
        'additional_services' => 'array',
        'additional_services_total' => 'decimal:2',
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
     * Get the sale staff member assigned to this booking.
     */
    public function saleStaff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sale_staff_id');
    }

    /**
     * Get the payments for the booking.
     */
    public function payment()
    {
        return $this->hasMany(Payment::class, 'booking_id', 'id')->orderBy('id', 'desc');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Payment::class, 'booking_id')->orderBy('id', 'desc');
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
     * Get check-ins for the booking.
     */
    public function checkIns(): HasMany
    {
        return $this->hasMany(CheckIn::class);
    }

    /**
     * Get special requests for the booking.
     */
    public function specialRequests(): HasMany
    {
        return $this->hasMany(GuestSpecialRequest::class);
    }

    /**
     * Get total passengers.
     */
    public function getTotalPassengersAttribute(): int
    {
        return $this->adults + $this->children + $this->infants;
    }

    /**
     * Check if booking can be paid.
     * 
     * @return array{can_pay: bool, message: string}
     */
    public function canPay(): array
    {
        // Kiểm tra nếu booking đã EXPIRED
        if ($this->status === 'expired') {
            return [
                'can_pay' => false,
                'message' => 'Đặt tour này đã hết hạn thanh toán. Vui lòng đặt lại tour mới.'
            ];
        }

        // Kiểm tra nếu booking PENDING nhưng đã hết hạn
        if ($this->status === 'pending' && $this->expires_at && $this->expires_at->isPast()) {
            // Tự động chuyển sang EXPIRED
            $this->update(['status' => 'expired']);
            return [
                'can_pay' => false,
                'message' => 'Đặt tour này đã hết hạn thanh toán. Vui lòng đặt lại tour mới.'
            ];
        }
        // Booking phải ở trạng thái 'confirmed' hoặc 'pending' để có thể thanh toán
        if (!in_array($this->status, ['confirmed', 'pending'])) {
            return [
                'can_pay' => false,
                'message' => 'Đặt tour này không thể thanh toán. Trạng thái: ' . $this->status
            ];
        }


        // Kiểm tra xem đã có payment completed chưa
        $hasCompletedPayment = $this->payment()
            ->where('status', 'completed')
            ->exists();

        if ($hasCompletedPayment) {
            return [
                'can_pay' => false,
                'message' => 'Đặt tour này đã được thanh toán thành công.'
            ];
        }

        // Kiểm tra departure có còn hợp lệ không
        if ($this->departure) {
            $departureDate = $this->departure->departure_date;
            if ($departureDate && $departureDate->isPast()) {
                return [
                    'can_pay' => false,
                    'message' => 'Không thể thanh toán vì ngày khởi hành đã qua.'
                ];
            }
        }

        // Trường hợp bình thường: có thể thanh toán
        return [
            'can_pay' => true,
            'message' => 'Bạn có thể thanh toán cho đặt tour này.'
        ];
    }

    /**
     * Check if booking is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function getRefundInfo()
    {
        // 1. Nếu chưa có lịch khởi hành hoặc chưa thanh toán -> Hoàn 100% (hoặc 0đ)
        if ($this->status !== 'paid' && $this->status !== 'cancel_requested') {
    return [
        'amount' => $this->total_amount, 
        'policy' => 'Khách chưa chốt lịch hoặc chưa thanh toán. Hủy không mất phí.'
    ];
}

        // 2. Tính khoảng cách ngày: (Ngày khởi hành) - (Hôm nay)
        $departureDate = Carbon::parse($this->departure->departure_date);
        $now = Carbon::now();
        $daysDiff = $now->diffInDays($departureDate, false); // false để lấy số âm nếu đã qua ngày

        // 3. Áp dụng chính sách hủy tour
        if ($daysDiff >= 30) {
            $percent = 100;
            $note = "Hủy trước 30 ngày. Hoàn 100%.";
        } elseif ($daysDiff >= 7) {
            $percent = 70;
            $note = "Hủy trước 7-29 ngày. Hoàn 70%.";
        } elseif ($daysDiff >= 3) {
            $percent = 30;
            $note = "Hủy trước 3-6 ngày. Hoàn 30%.";
        } else {
            $percent = 0;
            $note = "Hủy sát ngày (dưới 3 ngày) hoặc đã qua ngày đi. Không hoàn tiền.";
        }

        // 4. Tính ra số tiền cụ thể
        $refundAmount = ($this->total_amount * $percent) / 100;

        return [
            'days_diff' => $daysDiff,
            'percent' => $percent,
            'amount' => $refundAmount,
            'policy' => $note
        ];
    }
}
