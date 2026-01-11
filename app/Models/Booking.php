<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    HasMany,
    HasOne
};
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    /* =====================================================
     | FILLABLE
     ===================================================== */
    protected $fillable = [
        'user_id',
        'tour_id',
        'departure_id',

        // số lượng
        'adults',
        'children',
        'infants',

        // dịch vụ & giá
        'additional_services',
        'additional_services_total',
        'total_amount',
        'paid_amount',

        // thanh toán
        'payment_method',
        'receipt_image',

        // trạng thái & nguồn
        'status',
        'source',
        'booking_source',
        'promotion_code',
        'expires_at',

        // quản trị
        'sale_staff_id',
        'note',
        'cancel_reason',

        // hồ sơ đoàn
        'passenger_manifest_file',
        'contract_file',
        'service_details',
    ];

    /* =====================================================
     | CASTS
     ===================================================== */
    protected $casts = [
        'expires_at'               => 'datetime',
        'additional_services'      => 'array',
        'additional_services_total'=> 'decimal:2',
        'total_amount'             => 'decimal:2',
        'paid_amount'              => 'decimal:2',
    ];

    /* =====================================================
     | CONSTANTS
     ===================================================== */
    const SOURCE_WEBSITE  = 'website';
    const SOURCE_ZALO     = 'zalo';
    const SOURCE_FACEBOOK = 'facebook';
    const SOURCE_PHONE    = 'phone';

    const BOOKING_SOURCES = [
        self::SOURCE_WEBSITE  => 'Website',
        self::SOURCE_ZALO     => 'Zalo',
        self::SOURCE_FACEBOOK => 'Facebook',
        self::SOURCE_PHONE    => 'Điện thoại',
    ];

    /* =====================================================
     | RELATIONSHIPS
     ===================================================== */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function departure(): BelongsTo
    {
        return $this->belongsTo(TourDeparture::class, 'departure_id');
    }

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    public function saleStaff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sale_staff_id');
    }

    /* =================== Payments =================== */

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'booking_id')
            ->orderByDesc('id');
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    /* =================== Passengers =================== */

    public function passengers(): HasMany
    {
        return $this->hasMany(BookingPassenger::class, 'booking_id');
    }

    /* =================== Check-in =================== */

    public function checkIns(): HasMany
    {
        return $this->hasMany(CheckIn::class, 'booking_id');
    }

    /* =====================================================
     | ACCESSORS
     ===================================================== */

    public function getTotalPassengersAttribute(): int
    {
        return (int) $this->adults
             + (int) $this->children
             + (int) $this->infants;
    }

    /* =====================================================
     | BUSINESS LOGIC
     ===================================================== */

    /**
     * Kiểm tra booking có được thanh toán hay không
     */
    public function canPay(): array
    {
        // đã hết hạn
        if ($this->status === 'expired') {
            return [
                'can_pay' => false,
                'message' => 'Đơn hàng đã hết hạn thanh toán.'
            ];
        }

        // pending nhưng quá hạn
        if (
            $this->status === 'pending'
            && $this->expires_at
            && $this->expires_at->isPast()
        ) {
            $this->update(['status' => 'expired']);

            return [
                'can_pay' => false,
                'message' => 'Đơn hàng đã hết hạn thanh toán.'
            ];
        }

        // chỉ confirmed mới được thanh toán
        if ($this->status !== 'confirmed') {
            return [
                'can_pay' => false,
                'message' => match ($this->status) {
                    'pending' => 'Đơn hàng đang chờ Admin xác nhận.',
                    default   => 'Đơn hàng không ở trạng thái cho phép thanh toán.',
                }
            ];
        }

        // đã thanh toán rồi
        if ($this->payments()->where('status', 'completed')->exists()) {
            return [
                'can_pay' => false,
                'message' => 'Đơn hàng đã được thanh toán.'
            ];
        }

        // ngày khởi hành đã qua
        if (
            $this->departure
            && $this->departure->departure_date
            && $this->departure->departure_date->isPast()
        ) {
            return [
                'can_pay' => false,
                'message' => 'Ngày khởi hành đã qua, không thể thanh toán.'
            ];
        }

        return [
            'can_pay' => true,
            'message' => 'Có thể tiến hành thanh toán.'
        ];
    }

    /**
     * Booking đã hoàn tất hay chưa
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Tính chính sách hoàn tiền
     */
    public function getRefundInfo(): array
    {
        if (!$this->departure || $this->status !== 'paid') {
            return [
                'percent' => 100,
                'amount'  => $this->total_amount,
                'policy'  => 'Chưa khởi hành hoặc chưa thanh toán.'
            ];
        }

        $departureDate = Carbon::parse($this->departure->departure_date);
        $daysDiff = now()->diffInDays($departureDate, false);

        if ($daysDiff >= 30) {
            $percent = 100;
            $policy  = 'Hủy trước 30 ngày. Hoàn 100%.';
        } elseif ($daysDiff >= 7) {
            $percent = 70;
            $policy  = 'Hủy trước 7–29 ngày. Hoàn 70%.';
        } elseif ($daysDiff >= 3) {
            $percent = 30;
            $policy  = 'Hủy trước 3–6 ngày. Hoàn 30%.';
        } else {
            $percent = 0;
            $policy  = 'Hủy sát ngày hoặc đã khởi hành. Không hoàn tiền.';
        }

        return [
            'days_diff' => $daysDiff,
            'percent'   => $percent,
            'amount'    => ($this->total_amount * $percent) / 100,
            'policy'    => $policy,
        ];
    }
}
