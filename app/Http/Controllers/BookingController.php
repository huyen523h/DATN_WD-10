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


        // Tính tiền tour dựa theo giá của lịch khởi hành (TourDeparture)
        // Giống logic JS trong updateBookingSummary (adultTotal, childTotal, infantTotal)
        $adultPrice  = $departure->price;
        $childPrice  = $departure->child_price;
        $infantPrice = $departure->infant_price;

        $adultTotal  = $adultPrice  * $adults;
        $childTotal  = $childPrice  * $children;
        $infantTotal = $infantPrice * $infants;

        $baseTourAmount = $adultTotal + $childTotal + $infantTotal;

        // Dịch vụ thêm (lưu chi tiết để in hóa đơn)
        $selectedServices = $request->input('additional_services', []);
        $serviceDefinitions = [
            'insurance' => [
                'label' => 'Bảo hiểm du lịch',
                'amount' => 50000,
            ],
            'airport_pickup' => [
                'label' => 'Đón sân bay',
                'amount' => 200000,
            ],
            'single_room' => [
                'label' => 'Phòng đơn',
                'amount' => 300000,
            ],
            'guide_tip' => [
                'label' => 'Tip hướng dẫn viên',
                'amount' => 100000,
            ],
        ];

        $additionalServices = [];
        $additionalTotal = 0;
        foreach ($selectedServices as $key) {
            if (isset($serviceDefinitions[$key])) {
                $def = $serviceDefinitions[$key];
                $additionalServices[] = [
                    'key' => $key,
                    'label' => $def['label'],
                    'amount' => $def['amount'],
                ];
                $additionalTotal += $def['amount'];
            }
        }
        $subtotal = $baseTourAmount + $additionalTotal;

       $promotion = null;
        $discountAmount = 0;

        if (!empty($validated['promotion_code'] ?? null)) {
            $promotion = Promotion::where('code', $validated['promotion_code'])->first();
            
            // Kiểm tra kỹ lại lần cuối trước khi chốt đơn
            if ($promotion && $promotion->isActive() && $promotion->used_count < $promotion->quantity) {
                // Tính toán đúng theo loại (Số tiền hoặc %) thay vì cứng 10%
                if ($promotion->discount_percent) {
                    $discountAmount = $subtotal * ($promotion->discount_percent / 100);
                } else {
                    $discountAmount = $promotion->discount_amount;
                }
                
                // Đảm bảo không giảm quá số tiền tổng
                if ($discountAmount > $subtotal) {
                    $discountAmount = $subtotal;
                }
            }
        }

        // Tổng cuối cùng
        $totalAmount = $subtotal - $discountAmount;

        // Tạo Booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'tour_id' => $validated['tour_id'],
            'departure_id' => $validated['departure_id'],
            'promotion_id' => $promotion?->id, // Dấu ? để null safe
            'adults' => $adults,
            'children' => $children,
            'infants' => $infants,
            'additional_services' => $additionalServices,
            'additional_services_total' => $additionalTotal,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'discount_amount' => $discountAmount,
            'note' => $validated['note'],
        ]);

        // --- [THÊM MỚI] CẬP NHẬT SỐ LƯỢNG MÃ GIẢM GIÁ ---
        if ($promotion) {
            // 1. Tăng số lượng đã dùng lên 1
            $promotion->increment('used_count');
            \Illuminate\Support\Facades\DB::table('promotion_usages')->insert([
                'user_id' => Auth::id(),
                'promotion_id' => $promotion->id,
                'booking_id' => $booking->id, // Liên kết với đơn hàng vừa tạo
                'used_at' => now(),
                // 'created_at' => now(),
                // 'updated_at' => now(),
            ]);
        }

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

    public function cancel(Request $request, $id)
    {
        // 1. Tìm đơn hàng chính chủ
        $booking = Booking::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // 2. Validate cơ bản (Lý do hủy luôn bắt buộc)
        $request->validate([
            'cancel_reason' => 'required|string|min:2|max:255',
        ], [
            'cancel_reason.required' => 'Vui lòng nhập lý do hủy tour.',
        ]);

        // --- TRƯỜNG HỢP A: ĐÃ THANH TOÁN (Gửi yêu cầu hoàn tiền) ---
        if ($booking->status === 'paid') {
            // Validate thêm thông tin ngân hàng
            $request->validate([
                'refund_bank' => 'required|string',
                'refund_account' => 'required|string',
                'refund_holder' => 'required|string',
            ], [
                'refund_bank.required' => 'Vui lòng nhập tên ngân hàng.',
                'refund_account.required' => 'Vui lòng nhập số tài khoản nhận tiền.',
                'refund_holder.required' => 'Vui lòng nhập tên chủ tài khoản.',
            ]);

            // Cập nhật trạng thái "Yêu cầu hủy"
            $booking->update([
                'status' => 'cancel_requested', // Trạng thái mới (bạn cần thêm vào enum nếu DB set cứng, hoặc cứ để string)
                'cancel_reason' => 'Khách yêu cầu hủy: ' . $request->cancel_reason,
                'cancel_requested_at' => now(),
                'refund_bank' => $request->refund_bank,
                'refund_account' => $request->refund_account,
                'refund_holder' => $request->refund_holder,
            ]);

            return back()->with('success', 'Đã gửi yêu cầu hủy tour. Nhân viên sẽ liên hệ với bạn sớm nhất!');
        }

        // --- TRƯỜNG HỢP B: CHƯA THANH TOÁN (Hủy ngay lập tức) ---
        if (in_array($booking->status, ['pending', 'confirmed'])) {
            
            // Logic hoàn trả chỗ trống (Restock Seats)
            if ($booking->departure) {
                $seatsToRestore = $booking->adults + $booking->children;
                $booking->departure->increment('seats_available', $seatsToRestore);
            }

            // Cập nhật trạng thái Hủy
            $booking->update([
                'status' => 'cancelled',
                'cancel_reason' => 'Khách tự hủy: ' . $request->cancel_reason
            ]);

            return redirect()->route('bookings.show', $booking->id)
                ->with('success', 'Đã hủy đặt tour thành công.');
        }

        return back()->with('error', 'Trạng thái đơn hàng không hợp lệ để hủy.');
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

        // Kiểm tra trạng thái booking
        if (!in_array($booking->status, ['paid', 'completed'])) {
            return back()->with('error', 'Chỉ có thể upload danh sách đoàn khi đơn hàng đã thanh toán.');
        }

        $request->validate([
            // Cho phép: CSV, Excel, PDF, Word
            'manifest_file' => 'required|file|mimes:csv,xls,xlsx,pdf,doc,docx|max:5120', // Max 5MB
        ], [
            'manifest_file.required' => 'Vui lòng chọn file để upload.',
            'manifest_file.mimes' => 'File phải có định dạng: CSV, XLS, XLSX, PDF, DOC, DOCX.',
            'manifest_file.max' => 'File không được vượt quá 5MB.',
        ]);

        if ($request->hasFile('manifest_file')) {
            try {
                // Xóa file cũ nếu có
                if ($booking->passenger_manifest_file) {
                    Storage::disk('public')->delete($booking->passenger_manifest_file);
                }

                // Lưu file mới
                $path = $request->file('manifest_file')->store('manifests', 'public');
                
                $booking->update([
                    'passenger_manifest_file' => $path
                ]);
                
                // Refresh booking để đảm bảo dữ liệu mới nhất
                $booking->refresh();

                return redirect()->route('bookings.show', $booking->id)
                    ->with('success', 'Đã tải lên danh sách đoàn thành công! Bạn có thể xem file đã upload ở phần "Danh sách đoàn".');
            } catch (\Exception $e) {
                return back()->with('error', 'Có lỗi xảy ra khi upload file: ' . $e->getMessage());
            }
        }

        return back()->with('error', 'Vui lòng chọn file.');
    }
    // --- THÊM VÀO CUỐI FILE BookingController.php ---
    public function checkCoupon(Request $request)
    {
        // 1. Lấy dữ liệu gửi lên
        $code = strtoupper($request->code);
        $totalAmount = $request->total_amount; 
      $userId = Auth::id();

        // 2. Tìm mã trong DB
        $promotion = \App\Models\Promotion::where('code', $code)->first();

        // --- KIỂM TRA 5 LỚP BẢO MẬT ---

        // Check 1: Mã có tồn tại và đang hoạt động?
        if (!$promotion || $promotion->status !== 'active') {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá không tồn tại hoặc đã bị khóa.']);
        }

        // Check 2: Còn hạn sử dụng?
        $now = now();
        if ($now->lt($promotion->start_date) || $now->gt($promotion->end_date)) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá chưa mở hoặc đã hết hạn.']);
        }

        // Check 3: Còn số lượng trong kho?
        if ($promotion->used_count >= $promotion->quantity) {
            return response()->json(['success' => false, 'message' => 'Rất tiếc, mã này đã hết lượt sử dụng.']);
        }

        // Check 4: Đơn hàng đủ tiền tối thiểu?
        if ($totalAmount < $promotion->min_order_value) {
            return response()->json([
                'success' => false, 
                'message' => 'Đơn hàng phải từ ' . number_format($promotion->min_order_value) . 'đ mới được dùng mã này.'
            ]);
        }

        // Check 5: Khách đã dùng chưa? (Quan trọng)
        $hasUsed = \Illuminate\Support\Facades\DB::table('promotion_usages')
            ->where('user_id', $userId)
            ->where('promotion_id', $promotion->id)
            ->exists();

        if ($hasUsed) {
            return response()->json(['success' => false, 'message' => 'Bạn đã dùng mã này rồi (Mỗi khách chỉ được 1 lần).']);
        }

        // --- TÍNH TOÁN TIỀN GIẢM ---
        $discount = 0;
        if ($promotion->discount_type == 'percent') {
            $discount = ($totalAmount * $promotion->discount_amount) / 100;
        } else {
            $discount = $promotion->discount_amount;
        }

        // Không giảm quá số tiền tour
        if ($discount > $totalAmount) $discount = $totalAmount;

        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã thành công!',
            'discount_amount' => $discount,
            'promotion_id' => $promotion->id,
            'new_total' => $totalAmount - $discount
        ]);
    }
}
