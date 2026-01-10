<?php

// namespace App\Http\Controllers\Guide;

// use App\Http\Controllers\Controller;
// use App\Models\Booking;
// use App\Models\TourDeparture;
// use Illuminate\Http\Request;
// use Illuminate\View\View;

// class GuideDashboardController extends Controller
// {
//     // Middleware đã được đăng ký trong routes

//     /**
//      * Dashboard chính của HDV
//      */
//     public function index(): View
//     {
//         $user = auth()->user();

//         // Lấy các tour departure mà HDV được gán
//         $departures = TourDeparture::where('guide_id', $user->id)
//             ->with(['tour', 'bookings'])
//             ->whereDate('departure_date', '>=', now())
//             ->orderBy('departure_date', 'asc')
//             ->get();

//         // Thống kê
//         $totalTours = $departures->count();
//         $upcomingTours = $departures->where('departure_date', '>=', now())->count();
//         $totalGuests = $departures->sum(function ($departure) {
//             return $departure->bookings->where('status', '!=', 'cancelled')->sum(function ($booking) {
//                 return $booking->adults + $booking->children + $booking->infants;
//             });
//         });

//         return view('guide.dashboard', compact('departures', 'totalTours', 'upcomingTours', 'totalGuests'));
//     }

//     /**
//      * Xem chi tiết tour departure
//      */
//     public function showDeparture($id): View
//     {
//         $user = auth()->user();
//         $departure = TourDeparture::where('guide_id', $user->id)
//             ->with(['tour', 'bookings.user'])
//             ->findOrFail($id);

//         return view('guide.departure.show', compact('departure'));
//     }

//     /**
//      * Xem danh sách khách hàng của tour
//      */
//     public function showCustomers($departureId): View
//     {
//         $user = auth()->user();
//         $departure = TourDeparture::where('guide_id', $user->id)
//             ->with(['tour', 'bookings.user'])
//             ->findOrFail($departureId);

//         $bookings = $departure->bookings()
//             ->where('status', '!=', 'cancelled')
//             ->with('user')
//             ->get();

//         return view('guide.departure.customers', compact('departure', 'bookings'));
//     }
// }






namespace App\Http\Controllers\Guide;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TourDeparture;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GuideDashboardController extends Controller
{
    /**
     * Dashboard chính của HDV
     */
    public function index(): View
    {
        $user = auth()->user();

        // Các tour departure mà HDV được gán
        $departures = TourDeparture::where('guide_id', $user->id)
            ->with([
                'tour',
                'bookings' => function ($q) {
                    $q->where('status', '!=', 'cancelled');
                },
                'bookings.passengers'
            ])
            ->whereDate('departure_date', '>=', now())
            ->orderBy('departure_date', 'asc')
            ->get();

        // Thống kê
        $totalTours = $departures->count();

        $upcomingTours = $departures->where('departure_date', '>=', now())->count();

        // Tổng số hành khách (đếm theo passenger, không theo booking)
        $totalGuests = $departures->sum(function ($departure) {
            return $departure->bookings->sum(function ($booking) {
                return $booking->passengers->count();
            });
        });

        return view('guide.dashboard', compact(
            'departures',
            'totalTours',
            'upcomingTours',
            'totalGuests'
        ));
    }

    /**
     * Xem chi tiết tour departure (HDV)
     * Có: thông tin tour + booking + danh sách đoàn
     */
    public function showDeparture($id): View
    {
        $user = auth()->user();

        // Lấy tour + hành khách
        $departure = TourDeparture::where('guide_id', $user->id)
            ->with([
                'tour',
                'bookings' => function ($q) {
                    $q->where('status', '!=', 'cancelled');
                },
                'bookings.passengers',
                'bookings.user',
            ])
            ->findOrFail($id);

        // Gộp tất cả hành khách trong tour
        $passengers = $departure->bookings->flatMap->passengers;

        return view('guide.departure.show', compact(
            'departure',
            'passengers'
        ));
    }


    /**
     * Xem danh sách khách hàng / danh sách đoàn
     * (dành cho HDV check-in, điểm danh)
     */
    public function showCustomers($departureId): View
    {
        $user = auth()->user();

        $departure = TourDeparture::where('guide_id', $user->id)
            ->with([
                'tour',
                'bookings' => function ($q) {
                    $q->where('status', '!=', 'cancelled');
                },
                'bookings.user',
                'bookings.passengers' // 👈 QUAN TRỌNG
            ])
            ->findOrFail($departureId);

        // Booking (để hiển thị người liên hệ)
        $bookings = $departure->bookings;

        // Danh sách đoàn (theo hành khách)
        $passengers = $bookings->flatMap->passengers;

        return view(
            'guide.departure.customers',
            compact(
                'departure',
                'bookings',
                'passengers'
            )
        );
    }
}
