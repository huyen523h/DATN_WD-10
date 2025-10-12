<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    /**
     * Process payment for booking.
     */
    public function processPayment(Booking $booking, array $paymentData): Payment
    {
        return DB::transaction(function () use ($booking, $paymentData) {
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'payment_method' => $paymentData['payment_method'],
                'amount' => $booking->total_amount,
                'status' => 'pending',
                'transaction_code' => $this->generateTransactionCode(),
            ]);

            // Simulate payment processing
            $this->simulatePaymentProcessing($payment);

            return $payment;
        });
    }

    /**
     * Confirm payment.
     */
    public function confirmPayment(Payment $payment, array $responseData = []): bool
    {
        return DB::transaction(function () use ($payment, $responseData) {
            $payment->update([
                'status' => 'paid',
                'payment_date' => now(),
                'raw_response' => json_encode($responseData),
            ]);

            // Update booking status
            $payment->booking->update(['status' => 'paid']);

            return true;
        });
    }

    /**
     * Fail payment.
     */
    public function failPayment(Payment $payment, string $reason = ''): bool
    {
        return $payment->update([
            'status' => 'failed',
            'raw_response' => json_encode(['error' => $reason]),
        ]);
    }

    /**
     * Generate transaction code.
     */
    private function generateTransactionCode(): string
    {
        return 'TXN' . now()->format('YmdHis') . rand(1000, 9999);
    }

    /**
     * Simulate payment processing.
     */
    private function simulatePaymentProcessing(Payment $payment): void
    {
        // In real application, this would integrate with payment gateways
        // For now, we'll simulate a successful payment
        $this->confirmPayment($payment, [
            'gateway' => 'simulated',
            'transaction_id' => $payment->transaction_code,
            'status' => 'success',
        ]);
    }
}
