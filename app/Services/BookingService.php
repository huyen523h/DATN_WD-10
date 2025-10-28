<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Tour;
use App\Models\TourDeparture;
use App\Models\Promotion;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class BookingService
{
    /**
     * Create a new booking.
     */
    public function createBooking(array $data, int $userId): Booking
    {
        return DB::transaction(function () use ($data, $userId) {
            $tour = Tour::findOrFail($data['tour_id']);
            $departure = TourDeparture::findOrFail($data['departure_id']);

            // Validate seat availability
            $totalPassengers = $data['adults'] + $data['children'] + $data['infants'];
            if ($departure->seats_available < $totalPassengers) {
                throw new \Exception('Không đủ chỗ trống cho số lượng khách đã chọn.');
            }

            // Calculate total amount
            $totalAmount = $tour->price * $totalPassengers;

            // Apply promotion if provided
            $promotion = null;
            if (!empty($data['promotion_code'])) {
                $promotion = Promotion::where('code', $data['promotion_code'])->first();
                if ($promotion && $promotion->isActive()) {
                    $discount = $promotion->calculateDiscount($totalAmount);
                    $totalAmount -= $discount;
                }
            }

            // Create booking
            $booking = Booking::create([
                'user_id' => $userId,
                'tour_id' => $data['tour_id'],
                'departure_id' => $data['departure_id'],
                'promotion_id' => $promotion?->id,
                'adults' => $data['adults'],
                'children' => $data['children'] ?? 0,
                'infants' => $data['infants'] ?? 0,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'note' => $data['note'] ?? null,
            ]);

            // Update available seats
            $departure->decrement('seats_available', $totalPassengers);

            // Create invoice
            $this->createInvoice($booking);

            return $booking;
        });
    }

    /**
     * Create invoice for booking.
     */
    public function createInvoice(Booking $booking): Invoice
    {
        return Invoice::create([
            'booking_id' => $booking->id,
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'amount' => $booking->total_amount,
            'status' => 'issued',
        ]);
    }

    /**
     * Cancel a booking.
     */
    public function cancelBooking(Booking $booking): bool
    {
        if ($booking->status === 'cancelled') {
            return false;
        }

        return DB::transaction(function () use ($booking) {
            // Update booking status
            $booking->update(['status' => 'cancelled']);

            // Restore available seats
            if ($booking->departure) {
                $totalPassengers = $booking->adults + $booking->children + $booking->infants;
                $booking->departure->increment('seats_available', $totalPassengers);
            }

            // Cancel invoice
            if ($booking->invoice) {
                $booking->invoice->update(['status' => 'cancelled']);
            }

            return true;
        });
    }

    /**
     * Confirm a booking.
     */
    public function confirmBooking(Booking $booking, int $staffId): bool
    {
        if ($booking->status !== 'pending') {
            return false;
        }

        return $booking->update([
            'status' => 'confirmed',
            'staff_id' => $staffId,
        ]);
    }
}
