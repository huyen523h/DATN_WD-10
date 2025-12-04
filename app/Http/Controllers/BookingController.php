<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Tour;
use App\Models\TourDeparture;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

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

    public function create(Request $request): View
    {
        // code cũ chỉ được tạo ngày > ngày hiện tại . khôg được tạo  ngày trong quá khứ
        // $tour = Tour::with(['departures', 'images'])->findOrFail($request->tour_id);
        // $departures = $tour->departures()->where('seats_available', '>', 0)->whereDate('departure_date', '>=', now())->get();
        // // dd($departures);
        // $promotions = Promotion::where('status', 'active')
        //     ->where('start_date', '<=', now())
        //     ->where('end_date', '>=', now())
        //     ->get();

        // return view('bookings.create', compact('tour', 'departures', 'promotions'));

        $tour = Tour::with(['images'])->findOrFail($request->tour_id);

// ...........................CODE CHAT 4/11/2025 test Load cả ngày quá khứ 
        // Chúng ta sẽ tải TẤT CẢ các ngày khởi hành, bao gồm cả ngày quá khứ (để test) và ngày tương lai.
        $departures = $tour->departures()
                           ->where('seats_available', '>', 0)
                           // ->whereDate('departure_date', '>=', now()) // <-- TẠM TẮT DÒNG NÀY
                           ->get();

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

        $tour = Tour::findOrFail($validated['tour_id']);
        $departure = TourDeparture::findOrFail($validated['departure_id']);
        // kiểm tra ngày khởi hành hợp lệ  --- comment 4/11/2025
        // if ($departure->departure_date < now()->toDateString()) {
        //     return back()->withErrors(['departure_id' => 'Ngày khởi hành đã qua, vui lòng chọn ngày khác.']);
        // }

        if ($departure->seats_available < $validated['adults'] + $validated['children']) {
            return back()->withErrors(['seats' => 'Không đủ chỗ trống cho số lượng khách đã chọn.']);
        }
        $totalAmount = ($departure->price * $validated['adults']) +
            ($departure->child_price * ($validated['children'] ?? 0)) +
            ($departure->infant_price * ($validated['infants'] ?? 0));

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
            'adults' => $validated['adults'],
            'children' => $validated['children'] ?? 0,
            'infants' => $validated['infants'] ?? 0,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'note' => $validated['note'],
        ]);

        // Update available seats
        $totalPassengers = ($validated['adults'] ?? 0) + ($validated['children'] ?? 0) + ($validated['infants'] ?? 0);
        $departure->decrement('seats_available', $totalPassengers);

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
            return redirect()->route('bookings.index')->with('success', 'Đã hủy đặt tour thành công.');
        }

        return redirect()->back()->with('error', 'Không thể hủy tour đã hoàn thành hoặc đã thanh toán.');
    }
}
