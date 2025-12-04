<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Notification types
     */
    const TYPE_BOOKING_SUCCESS = 'booking_success';
    const TYPE_PAYMENT_SUCCESS = 'payment_success';
    const TYPE_PAYMENT_FAILED = 'payment_failed';
    const TYPE_DEPARTURE_UPCOMING = 'departure_upcoming';
    const TYPE_TOUR_SCHEDULE_CHANGED = 'tour_schedule_changed';
    const TYPE_REFUND = 'refund';
    const TYPE_BOOKING_CANCELLED = 'booking_cancelled';

    /**
     * Send notification to user
     */
    public function sendNotification(
        User $user,
        string $type,
        string $title,
        string $message,
        ?int $relatedId = null,
        ?string $relatedType = null,
        bool $sendEmail = true
    ): Notification {
        // Create notification in database
        $notification = Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'status' => 'unread',
            'related_id' => $relatedId,
            'related_type' => $relatedType,
        ]);

        // Send email if enabled
        if ($sendEmail && $user->email) {
            try {
                $this->sendEmailNotification($user, $type, $title, $message, $relatedId, $relatedType);
            } catch (\Exception $e) {
                Log::error('Failed to send notification email: ' . $e->getMessage(), [
                    'user_id' => $user->id,
                    'type' => $type,
                ]);
            }
        }

        return $notification;
    }

    /**
     * Send email notification
     */
    private function sendEmailNotification(
        User $user,
        string $type,
        string $title,
        string $message,
        ?int $relatedId = null,
        ?string $relatedType = null
    ): void {
        try {
            $emailTemplate = $this->getEmailTemplate($type);
            
            Mail::send($emailTemplate, [
                'user' => $user,
                'title' => $title,
                'notificationMessage' => $message, // Đổi tên để tránh conflict với $message trong closure
                'type' => $type,
                'relatedId' => $relatedId,
                'relatedType' => $relatedType,
            ], function ($mail) use ($user, $title) {
                $mail->to($user->email, $user->name)
                     ->subject($title . ' - Tour365');
            });
        } catch (\Exception $e) {
            Log::error('Failed to send email notification: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'type' => $type,
            ]);
        }
    }

    /**
     * Get email template based on notification type
     */
    private function getEmailTemplate(string $type): string
    {
        $templates = [
            self::TYPE_BOOKING_SUCCESS => 'emails.notifications.booking-success',
            self::TYPE_PAYMENT_SUCCESS => 'emails.notifications.payment-success',
            self::TYPE_PAYMENT_FAILED => 'emails.notifications.payment-failed',
            self::TYPE_DEPARTURE_UPCOMING => 'emails.notifications.departure-upcoming',
            self::TYPE_TOUR_SCHEDULE_CHANGED => 'emails.notifications.tour-schedule-changed',
            self::TYPE_REFUND => 'emails.notifications.refund',
            self::TYPE_BOOKING_CANCELLED => 'emails.notifications.booking-cancelled',
        ];

        return $templates[$type] ?? 'emails.notifications.default';
    }

    /**
     * Notify booking success
     */
    public function notifyBookingSuccess(Booking $booking): Notification
    {
        $user = $booking->user;
        $tour = $booking->tour;
        $departure = $booking->departure;

        $title = 'Đặt tour thành công!';
        $message = "Bạn đã đặt tour \"{$tour->title}\" thành công. Ngày khởi hành: " . 
                   ($departure ? $departure->departure_date->format('d/m/Y') : 'N/A') . 
                   ". Tổng tiền: " . number_format($booking->total_amount, 0, ',', '.') . " VNĐ. " .
                   "Vui lòng thanh toán để hoàn tất đặt tour.";

        return $this->sendNotification(
            $user,
            self::TYPE_BOOKING_SUCCESS,
            $title,
            $message,
            $booking->id,
            'booking',
            true
        );
    }

    /**
     * Notify payment success
     */
    public function notifyPaymentSuccess(Payment $payment): Notification
    {
        $booking = $payment->booking;
        $user = $booking->user;
        $tour = $booking->tour;

        $title = 'Thanh toán thành công!';
        $message = "Thanh toán cho tour \"{$tour->title}\" đã thành công. " .
                   "Số tiền: " . number_format($payment->amount, 0, ',', '.') . " VNĐ. " .
                   "Mã giao dịch: {$payment->transaction_code}.";

        return $this->sendNotification(
            $user,
            self::TYPE_PAYMENT_SUCCESS,
            $title,
            $message,
            $payment->id,
            'payment',
            true
        );
    }

    /**
     * Notify payment failed
     */
    public function notifyPaymentFailed(Payment $payment, string $reason = ''): Notification
    {
        $booking = $payment->booking;
        $user = $booking->user;
        $tour = $booking->tour;

        $title = 'Thanh toán thất bại';
        $message = "Thanh toán cho tour \"{$tour->title}\" đã thất bại. " .
                   ($reason ? "Lý do: {$reason}. " : '') .
                   "Vui lòng thử lại hoặc liên hệ hỗ trợ.";

        return $this->sendNotification(
            $user,
            self::TYPE_PAYMENT_FAILED,
            $title,
            $message,
            $payment->id,
            'payment',
            true
        );
    }

    /**
     * Notify departure upcoming
     */
    public function notifyDepartureUpcoming(Booking $booking, int $daysBefore = 3): Notification
    {
        $user = $booking->user;
        $tour = $booking->tour;
        $departure = $booking->departure;

        if (!$departure) {
            throw new \Exception('Departure not found for booking');
        }

        $title = "Lịch khởi hành sắp tới - Còn {$daysBefore} ngày";
        $message = "Tour \"{$tour->title}\" của bạn sẽ khởi hành vào ngày " .
                   $departure->departure_date->format('d/m/Y') . 
                   " (còn {$daysBefore} ngày). Vui lòng chuẩn bị sẵn sàng!";

        return $this->sendNotification(
            $user,
            self::TYPE_DEPARTURE_UPCOMING,
            $title,
            $message,
            $booking->id,
            'booking',
            true
        );
    }

    /**
     * Notify tour schedule changed
     */
    public function notifyTourScheduleChanged(Booking $booking, string $oldDate, string $newDate): Notification
    {
        $user = $booking->user;
        $tour = $booking->tour;

        $title = 'Lịch khởi hành đã thay đổi';
        $message = "Lịch khởi hành của tour \"{$tour->title}\" đã được thay đổi. " .
                   "Từ ngày: {$oldDate} → Ngày mới: {$newDate}. " .
                   "Vui lòng kiểm tra và xác nhận.";

        return $this->sendNotification(
            $user,
            self::TYPE_TOUR_SCHEDULE_CHANGED,
            $title,
            $message,
            $booking->id,
            'booking',
            true
        );
    }

    /**
     * Notify refund
     */
    public function notifyRefund(Booking $booking, float $amount, string $reason = ''): Notification
    {
        $user = $booking->user;
        $tour = $booking->tour;

        $title = 'Hoàn tiền thành công';
        $message = "Bạn đã được hoàn tiền cho tour \"{$tour->title}\". " .
                   "Số tiền: " . number_format($amount, 0, ',', '.') . " VNĐ. " .
                   ($reason ? "Lý do: {$reason}. " : '') .
                   "Tiền sẽ được chuyển về tài khoản trong 3-5 ngày làm việc.";

        return $this->sendNotification(
            $user,
            self::TYPE_REFUND,
            $title,
            $message,
            $booking->id,
            'booking',
            true
        );
    }

    /**
     * Notify booking cancelled
     */
    public function notifyBookingCancelled(Booking $booking, string $reason = ''): Notification
    {
        $user = $booking->user;
        $tour = $booking->tour;

        $title = 'Đặt tour đã bị hủy';
        $message = "Đặt tour \"{$tour->title}\" của bạn đã bị hủy. " .
                   ($reason ? "Lý do: {$reason}. " : '') .
                   "Nếu bạn có thắc mắc, vui lòng liên hệ hỗ trợ.";

        return $this->sendNotification(
            $user,
            self::TYPE_BOOKING_CANCELLED,
            $title,
            $message,
            $booking->id,
            'booking',
            true
        );
    }
}

