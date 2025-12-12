<?php

namespace App\Http\Controllers\Guide;

use App\Http\Controllers\Controller;
use App\Models\GuestSpecialRequest;
use App\Models\TourDeparture;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SpecialRequestController extends Controller
{
    /**
     * Danh sách yêu cầu đặc biệt của một departure
     */
    public function index(Request $request, $departureId): View
    {
        $user = auth()->user();
        
        $departure = TourDeparture::where('guide_id', $user->id)
            ->with(['tour'])
            ->findOrFail($departureId);

        $requests = GuestSpecialRequest::where('departure_id', $departureId)
            ->with(['booking.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('guide.special-requests.index', compact('departure', 'requests'));
    }

    /**
     * Tạo yêu cầu đặc biệt mới
     */
    public function create($departureId): View
    {
        $user = auth()->user();
        
        $departure = TourDeparture::where('guide_id', $user->id)
            ->with(['tour', 'bookings.user'])
            ->findOrFail($departureId);

        return view('guide.special-requests.create', compact('departure'));
    }

    /**
     * Lưu yêu cầu đặc biệt mới
     */
    public function store(Request $request, $departureId)
    {
        $user = auth()->user();
        
        $departure = TourDeparture::where('guide_id', $user->id)
            ->findOrFail($departureId);

        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'request_type' => 'required|in:dietary,medical,accessibility,other',
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'status' => 'nullable|in:pending,acknowledged,fulfilled,cancelled',
        ]);

        // Kiểm tra booking thuộc departure này
        $booking = Booking::where('id', $validated['booking_id'])
            ->where('departure_id', $departureId)
            ->firstOrFail();

        GuestSpecialRequest::create([
            'booking_id' => $validated['booking_id'],
            'departure_id' => $departureId,
            'request_type' => $validated['request_type'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => $validated['status'] ?? 'pending',
            'updated_by' => $user->id,
        ]);

        return redirect()
            ->route('guide.special-requests.index', $departureId)
            ->with('success', 'Đã thêm yêu cầu đặc biệt thành công.');
    }

    /**
     * Xem chi tiết yêu cầu
     */
    public function show($departureId, $requestId): View
    {
        $user = auth()->user();
        
        $departure = TourDeparture::where('guide_id', $user->id)
            ->with(['tour'])
            ->findOrFail($departureId);

        $specialRequest = GuestSpecialRequest::where('departure_id', $departureId)
            ->with(['booking.user', 'updatedBy'])
            ->findOrFail($requestId);

        return view('guide.special-requests.show', compact('departure', 'specialRequest'));
    }

    /**
     * Cập nhật yêu cầu đặc biệt
     */
    public function update(Request $request, $departureId, $requestId)
    {
        $user = auth()->user();
        
        $specialRequest = GuestSpecialRequest::where('departure_id', $departureId)
            ->findOrFail($requestId);

        $validated = $request->validate([
            'request_type' => 'required|in:dietary,medical,accessibility,other',
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'status' => 'required|in:pending,acknowledged,fulfilled,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);

        $specialRequest->update([
            'request_type' => $validated['request_type'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'notes' => $validated['notes'],
            'updated_by' => $user->id,
        ]);

        return redirect()
            ->route('guide.special-requests.show', [$departureId, $requestId])
            ->with('success', 'Đã cập nhật yêu cầu đặc biệt thành công.');
    }

    /**
     * Xóa yêu cầu đặc biệt
     */
    public function destroy($departureId, $requestId)
    {
        $user = auth()->user();
        
        $specialRequest = GuestSpecialRequest::where('departure_id', $departureId)
            ->findOrFail($requestId);

        $specialRequest->delete();

        return redirect()
            ->route('guide.special-requests.index', $departureId)
            ->with('success', 'Đã xóa yêu cầu đặc biệt thành công.');
    }
}
