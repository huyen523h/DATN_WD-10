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

class BookingActionMail extends Mailable
{
    public Booking $booking;
    public $futureDepartures;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;

        $this->futureDepartures = TourDeparture::where('tour_id', $booking->tour_id)
            ->whereDate('departure_date', '>=', Carbon::today())
            ->where('id', '!=', $booking->departure_id) 
            ->orderBy('departure_date', 'asc')
            ->get();
    }

    public function build()
    {
        return $this->subject('Xử lý booking tour #' . $this->booking->id)
            ->view('emails.booking-actions')
            ->with([
                'booking' => $this->booking,
                'futureDepartures' => $this->futureDepartures,
            ]);
    }
}
