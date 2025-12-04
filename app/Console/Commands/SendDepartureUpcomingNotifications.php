<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendDepartureUpcomingNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:departure-upcoming {--days=3 : Number of days before departure}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications for upcoming departures';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $targetDate = Carbon::now()->addDays($days)->format('Y-m-d');

        $this->info("Sending notifications for departures on {$targetDate}...");

        // Get bookings with departures on target date
        $bookings = Booking::whereHas('departure', function ($query) use ($targetDate) {
            $query->whereDate('departure_date', $targetDate);
        })
        ->where('status', '!=', 'cancelled')
        ->with(['departure', 'user', 'tour'])
        ->get();

        $notificationService = new NotificationService();
        $sentCount = 0;

        foreach ($bookings as $booking) {
            // Check if notification already sent for this booking and date
            $existingNotification = Notification::where('user_id', $booking->user_id)
                ->where('type', NotificationService::TYPE_DEPARTURE_UPCOMING)
                ->where('related_id', $booking->id)
                ->whereDate('created_at', Carbon::today())
                ->exists();

            if (!$existingNotification) {
                try {
                    $notificationService->notifyDepartureUpcoming($booking, $days);
                    $sentCount++;
                    $this->line("Sent notification to {$booking->user->name} for tour: {$booking->tour->title}");
                } catch (\Exception $e) {
                    $this->error("Failed to send notification for booking {$booking->id}: " . $e->getMessage());
                }
            }
        }

        $this->info("Sent {$sentCount} notifications successfully.");
        return Command::SUCCESS;
    }
}
