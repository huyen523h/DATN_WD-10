<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use App\Models\TourDeparture;
use Carbon\Carbon;

class BookingRescheduledMail extends Mailable
{
    public Booking $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        return $this->subject('Booking tour #' . $this->booking->id . ' đã được dời ngày')
            ->view('emails.booking-rescheduled')
            ->with(['booking' => $this->booking]);
    }
}
