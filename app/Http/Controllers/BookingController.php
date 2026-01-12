<?php

namespace App\Http\Controllers;

use App\Mail\BookingRefundedMail;
use App\Mail\BookingRescheduledMail;
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
use Illuminate\Support\Facades\Mail;

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

        // Tổng tạm tính = tiền tour + dịch vụ thêm (giống subtotal trong JS)
        $subtotal = $baseTourAmount + $additionalTotal;

        // Apply promotion nếu có (giảm trực tiếp 10% trên subtotal, giống JS: subtotal * 0.1)
        $promotion = null;
        $discountAmount = 0;
        if (!empty($validated['promotion_code'] ?? null)) {
            $promotion = Promotion::where('code', $validated['promotion_code'])->first();
            if ($promotion && $promotion->isActive()) {
                $discountAmount = $subtotal * 0.1;
            }
        }

        // Tổng cuối cùng = subtotal - discount (chính là số trong \"Tóm tắt đặt tour\")
        $totalAmount = $subtotal - $discountAmount;

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'tour_id' => $validated['tour_id'],
            'departure_id' => $validated['departure_id'],
            'promotion_id' => $promotion?->id,
            'adults' => $adults,
            'children' => $children,
            'infants' => $infants,
            'additional_services' => $additionalServices,
            'additional_services_total' => $additionalTotal,
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

    public function handle(Booking $booking, string $action)
    {
        abort_unless(
            in_array($action, ['refund', 'change_tour', 'reschedule']),
            404
        );

        switch ($action) {
            case 'refund':
                return redirect()->route('booking.refund', $booking);

            case 'change_tour':
                if ($booking->status !== 'cancelled') {
                    $booking->update([
                        'status' => 'cancelled',
                        'cancelled_at' => now(),
                    ]);
                }

                return redirect()
                    ->route('tours.index')
                    ->with('info', 'Booking cũ đã hủy. Vui lòng chọn tour mới!');

            case 'reschedule':
                return redirect()->route('booking.reschedule', $booking);
        }
    }


    public function refund(Booking $booking)
    {
        if ($booking->status === 'cancelled') {
            return redirect()
                ->route('welcome')
                ->with('info', 'Đơn đặt tour này đã được huỷ trước đó!');
        }

        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        Mail::to($booking->user->email)
            ->send(new BookingRefundedMail($booking));

        return redirect()
            ->route('welcome')
            ->with(
                'success',
                'Đơn đặt tour đã được huỷ thành công! Vui lòng đến trực tiếp cơ sở của chúng tôi để nhận tiền hoàn.'
            );
    }

    public function reschedule(Request $request, Booking $booking)
    {
        $newDepartureId = $request->query('departure_id');

        if (!$newDepartureId) {
            return redirect()->route('welcome')->with('error', 'Không tìm thấy ngày khởi hành mới!');
        }

        $newDeparture = TourDeparture::find($newDepartureId);

        if (!$newDeparture) {
            return redirect()->route('welcome')->with('error', 'Ngày khởi hành không hợp lệ!');
        }

        if ($newDeparture->id == $booking->departure_id) {
            return redirect()->route('welcome')->with('error', 'Bạn đã chọn ngày hiện tại!');
        }

        $booking->update([
            'departure_id' => $newDeparture->id,
            'status' => 'pending',
        ]);

        Mail::to($booking->user->email)
            ->send(new BookingRescheduledMail($booking));

        return redirect()->route('welcome')
            ->with('success', 'Booking đã được dời sang ngày ' . $newDeparture->departure_date->format('d/m/Y'));
    }

    /**
     * Xử lý hủy tour từ phía khách hàng
     */
    public function cancel(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Bạn không có quyền hủy đơn hàng này.');
        }

        // 2. Kiểm tra trạng thái hiện tại
        if ($booking->status === 'cancelled') {
            return redirect()->back()->with('error', 'Đơn hàng này đã bị hủy trước đó.');
        }

        // 3. Kiểm tra ngày khởi hành (Không cho hủy nếu tour đã chạy)
        if ($booking->departure) {
            $departureDate = \Carbon\Carbon::parse($booking->departure->departure_date);
            if ($departureDate->isPast()) {
                return redirect()->back()->with('error', 'Không thể hủy vì tour đã khởi hành hoặc kết thúc.');
            }
        }

        $reason = $request->input('cancel_reason', 'Khách hàng hủy chủ động');

        // TRƯỜNG HỢP 1: Đơn chưa thanh toán (Pending/Confirmed) -> Hủy ngay lập tức
        if ($booking->status === 'pending' || $booking->status === 'confirmed') {
            $booking->status = 'cancelled';
            $booking->cancel_reason = $reason;
            $booking->save();

            //  Cộng lại số chỗ trống cho Lịch khởi hành
            if ($booking->departure) {
                // Chỉ cộng lại ghế người lớn và trẻ em (em bé thường không chiếm ghế)
                $seatsToRestore = $booking->adults + $booking->children;
                $booking->departure->increment('seats_available', $seatsToRestore);
            }

            return redirect()->back()->with('success', 'Đơn hàng đã được hủy thành công.');
        }

        // TRƯỜNG HỢP 2: Đơn đã thanh toán (Paid) -> Chuyển sang trạng thái "Yêu cầu hủy" để Admin hoàn tiền
        elseif ($booking->status === 'paid') {
            
            // Lấy thông tin tài khoản ngân hàng khách nhập (nếu có)
            $bankInfo = "";
            if ($request->has('refund_bank')) {
                $bankInfo = "\n\n--- THÔNG TIN NHẬN TIỀN ---\n" .
                            "Ngân hàng: " . $request->refund_bank . "\n" .
                            "Số TK: " . $request->refund_account . "\n" .
                            "Chủ TK: " . $request->refund_holder;
            }

            $booking->status = 'cancel_requested'; // Trạng thái chờ Admin xử lý hoàn tiền
            $booking->cancel_reason = $reason . $bankInfo;
            $booking->save();

            return redirect()->back()->with('success', 'Yêu cầu hủy đã được gửi. Admin sẽ liên hệ để hoàn tiền theo chính sách.');
        }

        return redirect()->back()->with('error', 'Trạng thái đơn hàng không hợp lệ để hủy.');
    }
}
