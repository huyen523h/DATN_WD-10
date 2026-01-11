<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingActionMail;
use App\Models\Booking;

class StaffBookingMailController extends Controller
{
    public function send(Booking $booking)
    {
        Mail::to($booking->user->email)
            ->send(new BookingActionMail($booking));

        return back()->with('success', 'Đã gửi email cho khách!');
    }
}
