<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TourDeparture extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_id',
        'departure_date',
        'start_time',
        'end_time',
        'seats_total',
        'seats_available',
        'price',
        'child_price',
        'infant_price',
        'meeting_point',
        'status', // string: available|contact|sold_out
        // THÊM CÁC TRƯỜNG MỚI VÀO ĐÂY:
        'guide_id',
        'vehicle_id',
        'vehicle_type', // 16, 29, 45
        'vehicle_details',
        'driver_contact',
        'itinerary_file',
        // B2: Chốt đoàn
        'group_confirmed',
        'confirmed_guests_count',
        'group_confirmed_at',
        'group_confirmed_by',
        // Thông tin chi tiết khởi hành
        'departure_time',
        'departure_location',
        'departure_instructions',
        'backup_guide_id',
        'emergency_contact',
        'emergency_phone',
        'special_notes',
        'preparation_status',
        // Thông tin điều hành
        'tour_status', // preparing, running, completed, has_issue
        'management_notes', // Ghi chú điều hành
        'guest_list_file', // File PDF danh sách khách
        'assembly_time', // Giờ tập trung
        'pickup_point', // Điểm đón
        'bus_company', // Nhà xe
    ];
    // THÊM QUAN HỆ VỚI USER (GUIDE)
    public function guide()
    {
        return $this->belongsTo(User::class, 'guide_id');
    }

    public function backupGuide()
    {
        return $this->belongsTo(User::class, 'backup_guide_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    protected $casts = [
        'departure_date' => 'date',
        'start_time'     => 'datetime:H:i',
        'end_time'       => 'datetime:H:i',
        'departure_time' => 'datetime:H:i',
        'assembly_time' => 'datetime:H:i',
        'price'         => 'decimal:2',
        'child_price'   => 'decimal:2',
        'infant_price'  => 'decimal:2',
        'group_confirmed_at' => 'datetime',
    ];

    /**
     * status edit
     */

    public function getStatusAttribute($value)
    {
        if ($this->seats_available <= 0) {
            return 'sold_out';
        }

        return $value;
    }

    /**
     * Get the tour that owns the departure.
     */
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    /**
     * Get the bookings for the departure.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'departure_id');
    }

    /**
     * The operations linked to this departure.
     */
    public function operations(): HasMany
    {
        return $this->hasMany(TourOperation::class, 'tour_departure_id');
    }

    /**
     * Check if departure is available.
     */
    public function isAvailable(): bool
    {
        return $this->seats_available > 0;
    }
}
