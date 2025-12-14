<?php

namespace App\Http\Controllers\Guide;

use App\Http\Controllers\Controller;
use App\Models\CheckIn;
use App\Models\TourDeparture;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckInController extends Controller
{
    /**
     * Danh sách check-in của một departure
     */
    public function index(Request $request, $departureId): View
    {
        $user = auth()->user();
        
        $departure = TourDeparture::where('guide_id', $user->id)
            ->with(['tour', 'bookings.user'])
            ->findOrFail($departureId);

        $bookings = $departure->bookings()
            ->where('status', '!=', 'cancelled')
            ->with(['user', 'checkIns' => function($query) use ($departureId) {
                $query->where('departure_id', $departureId)
                    ->orderBy('check_in_time', 'desc');
            }])
            ->get();

        return view('guide.check-ins.index', compact('departure', 'bookings'));
    }

    /**
     * Check-in một booking
     */
    public function store(Request $request, $departureId)
    {
        $user = auth()->user();
        
        $departure = TourDeparture::where('guide_id', $user->id)
            ->findOrFail($departureId);

        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'check_in_time' => 'required|date',
            'check_in_location' => 'nullable|string|max:255',
            'status' => 'required|in:checked_in,checked_out,absent',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Kiểm tra booking thuộc departure này
        $booking = Booking::where('id', $validated['booking_id'])
            ->where('departure_id', $departureId)
            ->firstOrFail();

        CheckIn::create([
            'departure_id' => $departureId,
            'booking_id' => $validated['booking_id'],
            'checked_by' => $user->id,
            'check_in_time' => $validated['check_in_time'],
            'check_in_location' => $validated['check_in_location'],
            'status' => $validated['status'],
            'notes' => $validated['notes'],
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Đã ghi nhận check-in thành công.']);
        }

        return redirect()
            ->route('guide.check-ins.index', $departureId)
            ->with('success', 'Đã ghi nhận check-in thành công.');
    }

    /**
     * Lấy thông tin check-in để edit (JSON)
     */
    public function show($departureId, $checkInId)
    {
        $user = auth()->user();
        
        $checkIn = CheckIn::where('departure_id', $departureId)
            ->where('checked_by', $user->id)
            ->findOrFail($checkInId);

        return response()->json([
            'id' => $checkIn->id,
            'check_in_time' => $checkIn->check_in_time->format('Y-m-d\TH:i'),
            'check_in_location' => $checkIn->check_in_location,
            'status' => $checkIn->status,
            'notes' => $checkIn->notes,
        ]);
    }

    /**
     * Cập nhật check-in
     */
    public function update(Request $request, $departureId, $checkInId)
    {
        $user = auth()->user();
        
        $checkIn = CheckIn::where('departure_id', $departureId)
            ->where('checked_by', $user->id)
            ->findOrFail($checkInId);

        $validated = $request->validate([
            'check_in_time' => 'required|date',
            'check_in_location' => 'nullable|string|max:255',
            'status' => 'required|in:checked_in,checked_out,absent',
            'notes' => 'nullable|string|max:1000',
        ]);

        $checkIn->update($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Đã cập nhật check-in thành công.']);
        }

        return redirect()
            ->route('guide.check-ins.index', $departureId)
            ->with('success', 'Đã cập nhật check-in thành công.');
    }

    /**
     * Xóa check-in
     */
    public function destroy($departureId, $checkInId)
    {
        $user = auth()->user();
        
        $checkIn = CheckIn::where('departure_id', $departureId)
            ->where('checked_by', $user->id)
            ->findOrFail($checkInId);

        $checkIn->delete();

        return redirect()
            ->route('guide.check-ins.index', $departureId)
            ->with('success', 'Đã xóa check-in thành công.');
    }
}
