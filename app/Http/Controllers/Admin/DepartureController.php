<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Tour;
use App\Models\TourDeparture;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DepartureController extends Controller
{

    // Hiển thị danh sách khởi hành
    public function index(Request $request)
    {
        //Cập nhập trạng thái tour đã kt khi ngày khởi hành<hiệntaij
        $today = Carbon::today();
        TourDeparture::where('departure_date', '<', $today)
            ->where('status', '!=', 'finished')
            ->update(['status' => 'finished']);

        $query = TourDeparture::with(['tour', 'guide', 'backupGuide', 'vehicle']);
        
        // Lọc theo tour_id nếu có
        if ($request->has('tour_id') && $request->tour_id) {
            $query->where('tour_id', $request->tour_id);
        }
        
        $departures = $query->orderByDesc('id')->paginate(10);

        // Lấy tour nếu có tour_id để hiển thị context
        $tour = null;
        if ($request->has('tour_id') && $request->tour_id) {
            $tour = Tour::find($request->tour_id);
        }

        // Tính toán stats
        $totalDepartures = $departures->total();
        $availableDepartures = $query->clone()->where('status', 'available')->count();
        $soldOutDepartures = $query->clone()->where('status', 'sold_out')->count();
        $finishedDepartures = $query->clone()->where('status', 'finished')->count();
        $totalSeats = $query->clone()->sum('seats_total');
        $availableSeats = $query->clone()->sum('seats_available');
        $bookedSeats = $totalSeats - $availableSeats;

        return view('admin.tour_departures.index', compact('departures', 'tour', 'totalDepartures', 'availableDepartures', 'soldOutDepartures', 'finishedDepartures', 'totalSeats', 'availableSeats', 'bookedSeats'));
    }

    // Hiển thị form thêm mới
    public function create(Request $request)
    {
        $tours = Tour::all();
        
        // Lấy tour nếu có tour_id để hiển thị context
        $tour = null;
        if ($request->has('tour_id') && $request->tour_id) {
            $tour = Tour::find($request->tour_id);
        }
        
        return view('admin.tour_departures.create', compact('tours', 'tour'));
    }
    // Hiển thi chi tiết
    public function show($id)
    {
        $departure = TourDeparture::with(['tour.schedules' => function($query) use ($id) {
            $query->where(function($q) use ($id) {
                $q->whereNull('departure_id')
                  ->orWhere('departure_id', $id);
            })->with('guide')->orderBy('day_number');
        }, 'guide', 'backupGuide', 'vehicle'])->findOrFail($id);
        
        // Đồng bộ lại số chỗ trống dựa trên bookings thực tế
        // Chỉ tính người lớn + trẻ em, loại trừ booking đã hủy/expired
        $bookedSeats = Booking::where('departure_id', $departure->id)
            ->whereNotIn('status', ['cancelled', 'expired'])
            ->sum(DB::raw('adults + children'));

        $calculatedAvailable = max($departure->seats_total - $bookedSeats, 0);

        if ($departure->seats_available !== $calculatedAvailable) {
            $departure->seats_available = $calculatedAvailable;
            $departure->save();
        }
        
        // Lấy danh sách guides và vehicles để hiển thị trong form
        $guides = User::whereHas('roles', function($q) {
                $q->where('name', 'guide');
            })
            ->orWhereHas('roles', function($q) {
                $q->where('name', 'guide');
            })
            ->get();
        
        $vehicles = Vehicle::all();
        
        return view('admin.tour_departures.show', compact('departure', 'guides', 'vehicles'));
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

        $departure = TourDeparture::create($request->all());

        // Redirect về tour manage hub
        return redirect()->route('admin.tours.manage', $request->tour_id)
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
        
        // Redirect về tour manage hub
        return redirect()->route('admin.tours.manage', $departure->tour_id)
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

        $tourId = $departure->tour_id;
        $departure->delete();

        // Redirect về tour manage hub
        return redirect()->route('admin.tours.manage', $tourId)
            ->with('success', 'Xoá lịch khởi hành thành công!');
    }

    // Hàm cập nhật thông tin vận hành (HDV, Xe, Nhà xe, Giờ tập trung, Điểm đón)
    public function updateOperating(Request $request, $id)
    {
        $departure = TourDeparture::findOrFail($id);

        $validated = $request->validate([
            'guide_id' => 'nullable|exists:users,id', 
            'backup_guide_id' => 'nullable|exists:users,id|different:guide_id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'bus_company' => 'nullable|string|max:255',
            'assembly_time' => 'nullable|date_format:H:i',
            'pickup_point' => 'nullable|string',
            'departure_instructions' => 'nullable|string',
        ], [
            'backup_guide_id.different' => 'Hướng dẫn viên dự phòng phải khác hướng dẫn viên chính.',
        ]);

        // Nếu có vehicle_id, lấy thông tin xe
        if (isset($validated['vehicle_id']) && $validated['vehicle_id']) {
            $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
            
            // Chuẩn bị thông tin xe hiển thị
            $vehicleDetails = trim(($vehicle->brand ? $vehicle->brand . ' ' : '') .
                ($vehicle->color ? $vehicle->color . ' ' : '') .
                '(' . $vehicle->license_plate . ')');

            $driverContact = trim(($vehicle->driver_name ? $vehicle->driver_name . ' - ' : '') .
                ($vehicle->driver_phone ?? ''));

            // Cập nhật thông tin xe chi tiết
            $validated['vehicle_type'] = $vehicle->vehicle_type;
            $validated['vehicle_details'] = $vehicleDetails;
            $validated['driver_contact'] = $driverContact ?: null;
        }

        $departure->update($validated);

        return redirect()->route('admin.departures.show', $departure->id)
            ->with('success', 'Đã cập nhật thông tin vận hành thành công!');
    }

    // Cập nhật thông tin điều hành (Ghi chú, Trạng thái tour, File danh sách khách)
    public function updateManagement(Request $request, $id)
    {
        $departure = TourDeparture::findOrFail($id);

        $validated = $request->validate([
            'management_notes' => 'nullable|string',
            'tour_status' => 'required|in:preparing,running,completed,has_issue',
            'guest_list_file' => 'nullable|file|mimes:pdf|max:10240',
        ], [
            'tour_status.required' => 'Vui lòng chọn trạng thái tour.',
            'tour_status.in' => 'Trạng thái tour không hợp lệ.',
            'guest_list_file.mimes' => 'File danh sách khách phải là file PDF.',
            'guest_list_file.max' => 'File danh sách khách không được vượt quá 10MB.',
        ]);

        // Xử lý file upload danh sách khách
        if ($request->hasFile('guest_list_file')) {
            // Xóa file cũ nếu có
            if ($departure->guest_list_file) {
                Storage::disk('public')->delete($departure->guest_list_file);
            }
            // Lưu file mới
            $path = $request->file('guest_list_file')->store('guest_lists', 'public');
            $validated['guest_list_file'] = $path;
        }

        $departure->update($validated);

        return redirect()->route('admin.departures.show', $departure->id)
            ->with('success', 'Đã cập nhật thông tin điều hành thành công!');
    }
}
