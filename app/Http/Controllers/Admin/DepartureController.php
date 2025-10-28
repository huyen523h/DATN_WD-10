<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\TourDeparture;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class DepartureController extends Controller
{

    // Hiển thị danh sách khởi hành
    public function index()
    {
        //Cập nhập trạng thái tour đã kt khi ngày khởi hành<hiệntaij
        $today = Carbon::today();
        TourDeparture::where('departure_date', '<', $today)
            ->where('status', '!=', 'finished')
            ->update(['status' => 'finished']);

        $departures = TourDeparture::with('tour')
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.tour_departures.index', compact('departures'));
    }

    // Hiển thị form thêm mới
    public function create()
    {
        $tours = Tour::all();
        return view('admin.tour_departures.create', compact('tours'));
    }
    // Hiển thi chi tiết 
    public function show($id)
    {
        $departure = TourDeparture::findOrFail($id);
        return view('admin.tour_departures.show', compact('departure'));
    }

    // Lưu khởi hành mới
    public function store(Request $request)
    {
        $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'departure_date' => 'required|date',
            'seats_total' => 'required|integer|min:1',
            'seats_available' => 'required|integer|min:0',
            'status' => 'required|string',
            'price' => 'required|numeric|min:0',
            'child_price' => 'nullable|numeric|min:0',
            'infant_price' => 'nullable|numeric|min:0',
        ]);

        TourDeparture::create($request->all());

        return redirect()->route('admin.departures.index')
            ->with('success', 'Thêm khởi hành thành công!');
    }
    public function edit($id)
    {
        $departure = TourDeparture::findOrFail($id);
        $tours = Tour::all();

        return view('admin.tour_departures.edit', compact('departure', 'tours'));
    }

    public function update(Request $request, $id)
    {
        $departure = TourDeparture::findOrFail($id);

        $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'departure_date' => 'required|date',
            'seats_total' => 'required|integer|min:1',
            'seats_available' => 'required|integer|min:0',
            'status' => 'required|string',
            'price' => 'required|numeric|min:0',
            'child_price' => 'nullable|numeric|min:0',
            'infant_price' => 'nullable|numeric|min:0',
        ]);
        //Cấm chỉnh sửa nếu có khách đã thanh toán hoặc đã xác nhận
        $lockedCount = Booking::where('departure_id', $id)
            ->whereIn('status', ['completed', 'confirmed'])
            ->count();

        if ($lockedCount > 0) {
            return redirect()->back()
                ->with('error', 'Không thể thay đổi — tour này đã có khách thanh toán hoặc đã xác nhận.');
        }
        // Kiểm tra thay đổi ngày hoặc giá
        $oldDate = $departure->departure_date;
        $oldPrice = $departure->price;
        // Nếu thay đổi ngày hoặc giá → gửi thông báo cho khách có booking pending

        $departure->update($request->all());
        if ($oldDate != $departure->departure_date || $oldPrice != $departure->price) {
            $pendingBookings = Booking::where('departure_id', $departure->id)
                ->where('status', 'pending')
                ->get();

            foreach ($pendingBookings as $booking) {
                $user = $booking->user;
                // Gửi mail (nếu có cấu hình Mail)
                if ($user && $user->email) {
                    try {
                        Mail::raw(
                            "Lịch khởi hành tour {$departure->tour->name} bạn đã đặt tạm thời được cập nhật.\nNgày mới: " . $departure->departure_date->format('d/m/Y') . "\nVui lòng kiểm tra lại trước khi thanh toán.",
                            function ($message) use ($user) {
                                $message->to($user->email)
                                    ->subject('Cập nhật lịch khởi hành tour');
                            }
                        );
                    } catch (\Exception $e) {
                        // bỏ qua lỗi mail nếu chưa cấu hình
                    }
                }

                // Gửi notification (nếu có bảng notifications)
                if (method_exists($user, 'notifications')) {
                    $user->notifications()->create([
                        'title' => "Cập nhật lịch tour",
                        'content' => "Lịch khởi hành tour {$departure->tour->name} đã đổi sang ngày {$departure->departure_date->format('d/m/Y')}."
                    ]);
                }
            }
        }
        return redirect()->route('admin.departures.index')
            ->with('success', 'Cập nhật khởi hành thành công!');
    }

    // Xóa khởi hành
    public function destroy($id)
    {
        $departure = TourDeparture::findOrFail($id);

        // Đếm số booking có trạng thái khác 'cancelled' (tức là khách đã đặt và chưa hủy)
        $paidCount = Booking::where('departure_id', $id)
            ->whereIn('status', ['completed', 'confirmed'])
            ->count();

        $pendingCount = Booking::where('departure_id', $id)
            ->where('status', 'pending')
            ->count();

        if ($paidCount > 0) {
            return redirect()->back()
                ->with('error', 'Không thể xoá — tour này đã có khách thanh toán hoặc xác nhận.');
        }

        if ($pendingCount > 0) {
            return redirect()->back()
                ->with('warning', 'Ngày này có khách đang chờ thanh toán — vui lòng huỷ hoặc chuyển lịch trước khi xoá.');
        }

        $departure->delete();

        return redirect()->route('admin.departures.index')
            ->with('success', 'Xoá lịch khởi hành thành công!');
    }
}
