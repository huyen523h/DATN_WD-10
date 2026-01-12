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

//  chỉnh sửa số lượng người đặt
use App\Models\BookingPassenger;
use Illuminate\Support\Facades\DB;

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

        // If passenger birth years are provided, compute effective adult/child counts by age.
        if ($request->has('passengers')) {
            $computedAdults = 0;
            $computedChildren = 0;
            foreach ($request->input('passengers') as $groupType => $group) {
                foreach ($group as $p) {
                    $birth = $p['birth_year'] ?? null;
                    if ($birth && is_numeric($birth)) {
                        $age = (int) date('Y') - (int) $birth;
                        if ($age >= 12) {
                            $computedAdults++;
                        } else {
                            $computedChildren++;
                        }
                    } else {
                        // Fallback to provided grouping (adult/child)
                        if (($p['passenger_type'] ?? $groupType) === 'adult') {
                            $computedAdults++;
                        } else {
                            $computedChildren++;
                        }
                    }
                }
            }

            // Override counts with computed values to ensure pricing rules are applied
            $adults = max(0, $computedAdults);
            $children = max(0, $computedChildren);
        }

        // Business rules
        // Một người lớn đi kèm tối đa 2 trẻ em
        $childLimit = max(0, $adults * 2);
        // Em bé (infant) - giới hạn 1 infant / adult
        $infantLimit = max(0, $adults * 1);

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

        // Trẻ em phải đi cùng người lớn
        if ($adults === 0 && $children > 0) {
            return back()->withInput()->withErrors([
                'adults' => 'Trẻ em phải đi cùng ít nhất một người lớn. Vui lòng thêm người lớn.',
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


        // ================================
        // LƯU DANH SÁCH HÀNH KHÁCH
        // ================================
        if ($request->has('passengers')) {
            foreach ($request->passengers as $group) {
                foreach ($group as $p) {
                    BookingPassenger::create([
                        'booking_id'     => $booking->id,
                        'full_name'      => $p['full_name'],
                        'gender'         => $p['gender'] ?? null,
                        'birth_year'     => $p['birth_year'] ?? null,
                        'id_number'      => $p['id_number'] ?? null,
                        'passenger_type' => $p['passenger_type'],
                        'payment_status' => 'unpaid',
                    ]);
                }
            }
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
        $booking->load(['tour.images', 'departure', 'payment', 'passengers',]);

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

    // Manifest upload/delete removed — handled outside app now.

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
}
