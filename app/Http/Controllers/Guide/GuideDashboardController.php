<?php

namespace App\Http\Controllers\Guide;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TourDeparture;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GuideDashboardController extends Controller
{
    // Middleware đã được đăng ký trong routes

    /**
     * Dashboard chính của HDV
     */
    public function index(): View
    {
        $user = auth()->user();
        
        // Lấy các tour departure mà HDV được gán
        $departures = TourDeparture::where('guide_id', $user->id)
            ->with(['tour', 'bookings'])
            ->whereDate('departure_date', '>=', now())
            ->orderBy('departure_date', 'asc')
            ->get();
        
        // Thống kê
        $totalTours = $departures->count();
        $upcomingTours = $departures->where('departure_date', '>=', now())->count();
        $totalGuests = $departures->sum(function ($departure) {
            return $departure->bookings->where('status', '!=', 'cancelled')->sum(function ($booking) {
                return $booking->adults + $booking->children + $booking->infants;
            });
        });

        return view('guide.dashboard', compact('departures', 'totalTours', 'upcomingTours', 'totalGuests'));
    }

    /**
     * Xem chi tiết tour departure
     */
    public function showDeparture($id): View
    {
        $user = auth()->user();
        $departure = TourDeparture::where('guide_id', $user->id)
            ->with(['tour', 'bookings.user'])
            ->findOrFail($id);

        return view('guide.departure.show', compact('departure'));
    }

    /**
     * Xem danh sách khách hàng của tour
     */
    public function showCustomers($departureId): View
    {
        $user = auth()->user();
        $departure = TourDeparture::where('guide_id', $user->id)
            ->with(['tour', 'bookings.user'])
            ->findOrFail($departureId);

        $bookings = $departure->bookings()
            ->where('status', '!=', 'cancelled')
            ->with('user')
            ->get();

        return view('guide.departure.customers', compact('departure', 'bookings'));
    }
}

