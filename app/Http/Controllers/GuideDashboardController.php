<?php

namespace App\Http\Controllers;

use App\Models\TourDeparture;
use App\Models\CheckInOut;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class GuideDashboardController extends Controller
{
    /**
     * Trang tổng quan cho hướng dẫn viên.
     */
    public function dashboard(Request $request): View
    {
        $upcoming = $this->queryGuideDepartures($request)
            ->whereDate('departure_date', '>=', now()->toDateString())
            ->with(['bookings']) // Eager load bookings để tính số khách
            ->orderBy('departure_date')
            ->limit(10)
            ->get();

        return view('guide.dashboard', [
            'upcoming' => $upcoming,
        ]);
    }

    /**
     * Danh sách tất cả lịch khởi hành được phân công.
     */
    public function departures(Request $request): View
    {
        $departures = $this->queryGuideDepartures($request)
            ->with(['bookings']) // Eager load bookings để tính số khách
            ->orderBy('departure_date')
            ->paginate(10)
            ->appends($request->only(['from', 'to']));

        return view('guide.departures.index', compact('departures'));
    }

    /**
     * Chi tiết 1 lịch khởi hành.
     */
    public function showDeparture(TourDeparture $departure): View
    {
        abort_unless($departure->guide_id === auth()->id(), 403);

        $departure->load(['tour', 'bookings.user']);
        
        // Load check-in/out data for each booking
        $bookingIds = $departure->bookings->pluck('id');
        $checkInOuts = CheckInOut::whereIn('booking_id', $bookingIds)
            ->with('user')
            ->get()
            ->groupBy('booking_id');
        
        // Add check-in/out status to each booking
        foreach ($departure->bookings as $booking) {
            $booking->check_in = $checkInOuts->get($booking->id)?->where('type', 'check_in')->first();
            $booking->check_out = $checkInOuts->get($booking->id)?->where('type', 'check_out')->first();
        }

        return view('guide.departures.show', compact('departure'));
    }

    /**
     * Danh sách check-in/check-out cho guide.
     */
    public function checkInOuts(Request $request): View
    {
        $guideId = auth()->id();
        
        // Lấy các departure của guide
        $departureIds = TourDeparture::where('guide_id', $guideId)
            ->pluck('id');
        
        // Lấy các booking thuộc các departure này
        $bookingIds = Booking::whereIn('departure_id', $departureIds)
            ->pluck('id');
        
        // Lấy check-in/out của các booking này
        $query = CheckInOut::with(['user', 'booking.tour', 'booking.departure'])
            ->whereIn('booking_id', $bookingIds);
        
        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by departure
        if ($request->filled('departure_id')) {
            $departureBookingIds = Booking::where('departure_id', $request->departure_id)
                ->pluck('id');
            $query->whereIn('booking_id', $departureBookingIds);
        }
        
        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('check_time', $request->date);
        }
        
        $checkInOuts = $query->orderBy('check_time', 'desc')
            ->paginate(20)
            ->appends($request->only(['type', 'status', 'departure_id', 'date']));
        
        // Get departures for filter
        $departures = TourDeparture::where('guide_id', $guideId)
            ->with('tour')
            ->orderBy('departure_date', 'desc')
            ->get();
        
        // Statistics
        $stats = [
            'total_today' => CheckInOut::whereIn('booking_id', $bookingIds)
                ->whereDate('check_time', today())
                ->count(),
            'check_ins_today' => CheckInOut::whereIn('booking_id', $bookingIds)
                ->whereDate('check_time', today())
                ->where('type', 'check_in')
                ->count(),
            'check_outs_today' => CheckInOut::whereIn('booking_id', $bookingIds)
                ->whereDate('check_time', today())
                ->where('type', 'check_out')
                ->count(),
            'pending_count' => CheckInOut::whereIn('booking_id', $bookingIds)
                ->where('status', 'pending')
                ->count(),
        ];
        
        return view('guide.check-in-out.index', compact('checkInOuts', 'departures', 'stats'));
    }
    
    /**
     * Check-in/check-out khách hàng cho một departure.
     */
    public function checkInOut(Request $request, TourDeparture $departure): RedirectResponse
    {
        abort_unless($departure->guide_id === auth()->id(), 403);
        
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'type' => 'required|in:check_in,check_out',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        $booking = Booking::findOrFail($request->booking_id);
        
        // Verify booking belongs to this departure
        if ($booking->departure_id !== $departure->id) {
            return back()->with('error', 'Booking không thuộc lịch khởi hành này.');
        }
        
        // Check if already checked in/out
        $existingCheck = CheckInOut::where('user_id', $booking->user_id)
            ->where('booking_id', $booking->id)
            ->where('type', $request->type)
            ->where('status', '!=', 'cancelled')
            ->first();
        
        if ($existingCheck) {
            return back()->with('error', 'Khách hàng đã thực hiện ' . ($request->type === 'check_in' ? 'check-in' : 'check-out') . ' cho tour này.');
        }
        
        $checkInOut = CheckInOut::create([
            'user_id' => $booking->user_id,
            'booking_id' => $booking->id,
            'type' => $request->type,
            'check_time' => now(),
            'location' => $request->location ?? $departure->meeting_point,
            'notes' => $request->notes,
            'status' => 'confirmed', // Guide có thể xác nhận ngay
            'verified_by' => auth()->user()->name,
            'verified_at' => now(),
        ]);
        
        $typeLabel = $request->type === 'check_in' ? 'check-in' : 'check-out';
        return redirect()->route('guide.departures.show', $departure)
            ->with('success', "Đã thực hiện {$typeLabel} cho khách hàng {$booking->user->name} thành công!");
    }
    
    /**
     * Xác nhận check-in/check-out.
     */
    public function confirmCheckInOut(CheckInOut $checkInOut): RedirectResponse
    {
        // Verify guide has access to this check-in/out
        $departure = $checkInOut->booking->departure;
        abort_unless($departure && $departure->guide_id === auth()->id(), 403);
        
        $checkInOut->confirm(auth()->user()->name);
        
        return back()->with('success', 'Đã xác nhận ' . $checkInOut->type_label . ' thành công!');
    }
    
    /**
     * Hủy check-in/check-out.
     */
    public function cancelCheckInOut(CheckInOut $checkInOut): RedirectResponse
    {
        // Verify guide has access to this check-in/out
        $departure = $checkInOut->booking->departure;
        abort_unless($departure && $departure->guide_id === auth()->id(), 403);
        
        $checkInOut->cancel();
        
        return back()->with('success', 'Đã hủy ' . $checkInOut->type_label . ' thành công!');
    }

    protected function queryGuideDepartures(Request $request)
    {
        $query = TourDeparture::with(['tour'])
            ->where('guide_id', auth()->id());

        if ($request->filled('from')) {
            $query->whereDate('departure_date', '>=', $request->date('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('departure_date', '<=', $request->date('to'));
        }

        return $query;
    }
}


