<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Tour;
use App\Models\TourDeparture;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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
        $oldDateFormatted = $departure->departure_date->format('d/m/Y');

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
            // Gửi notification nội bộ khi lịch thay đổi
            $newDateFormatted = $departure->departure_date->format('d/m/Y');
            $bookings = Booking::where('departure_id', $departure->id)
                ->where('status', '!=', 'cancelled')
                ->with(['user', 'tour'])
                ->get();

            $notificationService = new NotificationService();
            foreach ($bookings as $booking) {
                $notificationService->notifyTourScheduleChanged($booking, $oldDateFormatted, $newDateFormatted);
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

    // Hàm cập nhật thông tin điều hành (HDV, Xe, Lịch trình)
    public function updateOperating(Request $request, $id)
    {
      $departure = TourDeparture::findOrFail($id);

        $validated = $request->validate([
            // Sửa 'nullable' thành 'required'
            'guide_id' => 'required|exists:users,id', 
            'vehicle_details' => 'required|string|max:255',
            'driver_contact' => 'required|string|max:255',
            
            // File thì vẫn nên để 'nullable' để lần sau sửa SĐT tài xế thì không bắt buộc phải up lại file cũ
          'itinerary_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:10240', // Tăng lên 10MB và thêm đuôi file
        ], [
            // Thêm thông báo lỗi tiếng Việt cho thân thiện
            'guide_id.required' => 'Vui lòng chọn Hướng dẫn viên.',
            'vehicle_details.required' => 'Vui lòng nhập thông tin Xe & Biển số.',
            'driver_contact.required' => 'Vui lòng nhập liên hệ Tài xế.',
        ]);

        // Xử lý file upload
        if ($request->hasFile('itinerary_file')) {
            // Xóa file cũ nếu có
            if ($departure->itinerary_file) {
                Storage::disk('public')->delete($departure->itinerary_file);
            }
            // Lưu file mới
            $path = $request->file('itinerary_file')->store('itineraries', 'public');
            $validated['itinerary_file'] = $path;
        }

        $departure->update($validated);

        return back()->with('success', 'Đã cập nhật thông tin điều hành thành công!');
    }
}
