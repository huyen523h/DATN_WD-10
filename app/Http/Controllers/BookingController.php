<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Tour;
use App\Models\TourDeparture;
use App\Models\Promotion;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Payment;

class BookingController extends Controller
{
    /**
     * Display a listing of bookings.
     */
    public function index(): View
    {
      // Lấy danh sách đơn hàng của user đang đăng nhập
        $bookings = Booking::with(['tour', 'payment']) // Eager load để tránh N+1 query
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create(Request $request): View
    {
        $tour = Tour::with(['departures', 'images'])->findOrFail($request->tour_id);
        $departures = $tour->departures()->where('seats_available', '>', 0)->whereDate('departure_date', '>=', now())->get();
        // dd($departures);
        $promotions = Promotion::where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        return view('bookings.create', compact('tour', 'departures', 'promotions'));
    }

    /**
     * Store a newly created booking.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'departure_id' => 'required|exists:tour_departures,id',
            'adults' => 'required|integer|min:1',
            'children' => 'integer|min:0',
            'infants' => 'integer|min:0',
            'promotion_code' => 'nullable|exists:promotions,code',
            'note' => 'nullable|string|max:1000',
        ]);

        $adults = $validated['adults'];
        $children = $validated['children'] ?? 0;
        $infants = $validated['infants'] ?? 0;

        // Quy tắc kèm trẻ/em bé
        $childLimit = $adults * 2;
        $infantLimit = $adults * 1;

        if ($children > $childLimit) {
            return back()->withInput()->withErrors([
                'children' => "{$adults} người lớn chỉ có thể đi kèm tối đa {$childLimit} trẻ em. Vui lòng tăng số người lớn hoặc giảm số trẻ.",
            ]);
        }

        if ($infants > $infantLimit) {
            return back()->withInput()->withErrors([
                'infants' => "{$adults} người lớn chỉ có thể đi kèm tối đa {$infantLimit} em bé. Vui lòng tăng số người lớn hoặc giảm số em bé.",
            ]);
        }

        $tour = Tour::findOrFail($validated['tour_id']);
        $departure = TourDeparture::findOrFail($validated['departure_id']);
        // kiểm tra ngày khởi hành hợp lệ
        if ($departure->departure_date < now()->toDateString()) {
            return back()->withErrors(['departure_id' => 'Ngày khởi hành đã qua, vui lòng chọn ngày khác.']);
        }

        // Check seat availability (em bé ngồi chung, không trừ chỗ)
        $seatPassengers = $adults + $children; // chỉ tính người lớn + trẻ em
        $totalPassengers = $seatPassengers + $infants;
        if ($departure->seats_available < $seatPassengers) {
            return back()->withInput()->withErrors(['seats' => 'Không đủ chỗ trống cho số lượng người lớn và trẻ em đã chọn.']);
        }


        //Tính tổng tiền dựa theo giá của lịch khởi hành (TourDeparture)
        $totalAmount = ($departure->price * $adults) +
            ($departure->child_price * $children) +
            ($departure->infant_price * $infants);

        // Apply promotion if provided
        $promotion = null;
        if (!empty($validated['promotion_code'] ?? null)) {
            $promotion = Promotion::where('code', $validated['promotion_code'])->first();
            if ($promotion && $promotion->isActive()) {
                $discount = $promotion->calculateDiscount($totalAmount);
                $totalAmount -= $discount;
            }
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'tour_id' => $validated['tour_id'],
            'departure_id' => $validated['departure_id'],
            'promotion_id' => $promotion?->id,
            'adults' => $adults,
            'children' => $children,
            'infants' => $infants,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'note' => $validated['note'],
        ]);

        // Update available seats
        // Giảm chỗ trống theo số ghế cần (người lớn + trẻ em)
        $departure->decrement('seats_available', $seatPassengers);

        // Send notification
        $notificationService = new NotificationService();
        $notificationService->notifyBookingSuccess($booking);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Đặt tour thành công! Vui lòng thanh toán để hoàn tất.');
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking): View
    {
        $booking->load(['tour.images', 'departure', 'payment']);

        return view('bookings.show', compact('booking'));
    }
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);

        // Chỉ cho phép huỷ nếu chưa thanh toán hoặc đang chờ
        if (in_array($booking->status, ['pending', 'confirmed'])) {
            $booking->update(['status' => 'cancelled']);
            
            // Send notification
            $notificationService = new NotificationService();
            $notificationService->notifyBookingCancelled($booking, 'Người dùng tự hủy');

            return redirect()->route('bookings.index')->with('success', 'Đã hủy đặt tour thành công.');
        }

        return redirect()->back()->with('error', 'Không thể hủy tour đã hoàn thành hoặc đã thanh toán.');
    }

    public function uploadManifest(Request $request, $id)
    {
        $booking = Booking::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            // Cho phép: Ảnh, PDF, Word, Excel
            'manifest_file' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:5120', // Max 5MB
        ]);

        if ($request->hasFile('manifest_file')) {
            // Xóa file cũ nếu có
            if ($booking->passenger_manifest_file) {
                Storage::disk('public')->delete($booking->passenger_manifest_file);
            }

            // Lưu file mới
            $path = $request->file('manifest_file')->store('manifests', 'public');
            
            $booking->update([
                'passenger_manifest_file' => $path
            ]);

            return back()->with('success', 'Đã tải lên danh sách đoàn thành công!');
        }

        return back()->with('error', 'Vui lòng chọn file.');
    }
}
