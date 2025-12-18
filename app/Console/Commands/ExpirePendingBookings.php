<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpirePendingBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Chuyển các booking PENDING đã hết hạn sang EXPIRED';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Đang kiểm tra các booking đã hết hạn...');

        // Tìm các booking PENDING đã hết hạn (expires_at < now)
        $expiredBookings = Booking::where('status', 'pending')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->get();

        $count = $expiredBookings->count();

        if ($count === 0) {
            $this->info('Không có booking nào cần expire.');
            return Command::SUCCESS;
        }

        $this->info("Tìm thấy {$count} booking cần expire.");

        $expired = 0;
        foreach ($expiredBookings as $booking) {
            try {
                // Chuyển status sang EXPIRED
                $booking->update(['status' => 'expired']);
                
                // Trả lại số chỗ đã giữ
                if ($booking->departure) {
                    $totalPassengers = $booking->adults + $booking->children + $booking->infants;
                    $booking->departure->increment('seats_available', $totalPassengers);
                }

                $expired++;
                $this->line("  - Booking #{$booking->id} đã được chuyển sang EXPIRED");
                
                Log::info("Booking expired automatically", [
                    'booking_id' => $booking->id,
                    'user_id' => $booking->user_id,
                    'expires_at' => $booking->expires_at
                ]);
            } catch (\Exception $e) {
                $this->error("  - Lỗi khi expire booking #{$booking->id}: " . $e->getMessage());
                Log::error("Failed to expire booking", [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $this->info("Đã expire {$expired}/{$count} booking.");
        return Command::SUCCESS;
    }
}
