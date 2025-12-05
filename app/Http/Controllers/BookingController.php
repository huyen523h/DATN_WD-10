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

class BookingController extends Controller
{
    /**
     * Display a listing of bookings.
     */
    public function index(): View
    {
        // Chặn guide truy cập route booking của khách hàng
        if (auth()->user()->isGuide()) {
            abort(403, 'Hướng dẫn viên không thể truy cập trang đặt tour của khách hàng. Vui lòng sử dụng trang quản lý lịch khởi hành.');
        }

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
        // Chặn guide truy cập route booking của khách hàng
        if (auth()->user()->isGuide()) {
            abort(403, 'Hướng dẫn viên không thể đặt tour. Vui lòng sử dụng trang quản lý lịch khởi hành.');
        }

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
        // Chặn guide truy cập route booking của khách hàng
        if (auth()->user()->isGuide()) {
            abort(403, 'Hướng dẫn viên không thể đặt tour. Vui lòng sử dụng trang quản lý lịch khởi hành.');
        }

        $validated = $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'departure_id' => 'required|exists:tour_departures,id',
            'adults' => 'required|integer|min:1',
            'children' => 'integer|min:0',
            'infants' => 'integer|min:0',
            'promotion_code' => 'nullable|exists:promotions,code',
            'note' => 'nullable|string|max:1000',
        ]);

        // Validate passenger limits: Mỗi người lớn tối đa 2 trẻ em và 1 em bé
        $adults = $validated['adults'];
        $children = $validated['children'] ?? 0;
        $infants = $validated['infants'] ?? 0;
        
        $maxChildren = $adults * 2;
        $maxInfants = $adults * 1;
        
        if ($children > $maxChildren) {
            return back()->withErrors([
                'children' => "{$adults} người lớn chỉ có thể đi kèm tối đa {$maxChildren} trẻ em. Vui lòng tăng số người lớn hoặc giảm số trẻ em."
            ])->withInput();
        }
        
        if ($infants > $maxInfants) {
            return back()->withErrors([
                'infants' => "{$adults} người lớn chỉ có thể đi kèm tối đa {$maxInfants} em bé. Vui lòng tăng số người lớn hoặc giảm số em bé."
            ])->withInput();
        }

        $tour = Tour::findOrFail($validated['tour_id']);
        $departure = TourDeparture::findOrFail($validated['departure_id']);
        // kiểm tra ngày khởi hành hợp lệ
        if ($departure->departure_date < now()->toDateString()) {
            return back()->withErrors(['departure_id' => 'Ngày khởi hành đã qua, vui lòng chọn ngày khác.']);
        }

        // Check seat availability
        if ($departure->seats_available < $validated['adults'] + $validated['children']) {
            return back()->withErrors(['seats' => 'Không đủ chỗ trống cho số lượng khách đã chọn.']);
        }


        //Tính tổng tiền dựa theo giá của lịch khởi hành (TourDeparture)
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
            'expires_at' => now()->addMinutes(15), // Giữ chỗ 15 phút để thanh toán
        ]);

        // Update available seats
        $totalPassengers = ($validated['adults'] ?? 0) + ($validated['children'] ?? 0) + ($validated['infants'] ?? 0);
        $departure->decrement('seats_available', $totalPassengers);

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
        // Chặn guide truy cập route booking của khách hàng
        if (auth()->user()->isGuide()) {
            abort(403, 'Hướng dẫn viên không thể xem đặt tour của khách hàng. Vui lòng sử dụng trang quản lý lịch khởi hành.');
        }

        // Kiểm tra user chỉ xem được booking của chính mình
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền xem đặt tour này.');
        }

        $booking->load(['tour.images', 'departure.guide', 'payment']);

        return view('bookings.show', compact('booking'));
    }
    public function destroy($id)
    {
        // Chặn guide truy cập route booking của khách hàng
        if (auth()->user()->isGuide()) {
            abort(403, 'Hướng dẫn viên không thể hủy đặt tour của khách hàng. Vui lòng sử dụng trang quản lý lịch khởi hành.');
        }

        $booking = Booking::findOrFail($id);

        // Kiểm tra user chỉ hủy được booking của chính mình
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền hủy đặt tour này.');
        }

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
