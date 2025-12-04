<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GroupTourRequest;
use App\Models\User;
use App\Models\Tour;
use App\Models\TourDeparture;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log; // Thêm dòng này để log lỗi nếu cần

class GroupRequestController extends Controller
{
    // 1. Danh sách
    public function index()
{
    $requests = GroupTourRequest::latest()->paginate(10);
    return view('components.admin.group-requests.index', compact('requests'));
}

    // 2. Xem chi tiết
   public function show($id)
{
    $request = GroupTourRequest::findOrFail($id);
    return view('components.admin.group-requests.show', compact('request'));
}
    // 3. Cập nhật trạng thái & Ghi chú
    public function update(Request $request, $id)
    {
        $groupRequest = GroupTourRequest::findOrFail($id);
        
        $groupRequest->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes
        ]);

        return back()->with('success', 'Đã cập nhật trạng thái yêu cầu thành công!');
    }

    // 4. Xóa
    public function destroy($id)
    {
        $request = GroupTourRequest::findOrFail($id);
        $request->delete();
        return redirect()->route('admin.group-requests.index')->with('success', 'Đã xóa yêu cầu.');
    }

    // --- (HÀM MỚI) CHUYỂN ĐỔI YÊU CẦU THÀNH BOOKING ---
    public function convertToBooking(Request $request, $id)
    {
        $groupRequest = GroupTourRequest::findOrFail($id);

        // Validate dữ liệu nhập vào từ Modal
        $request->validate([
            'final_price' => 'required|numeric|min:0', // Giá chốt tổng cộng
            'departure_date' => 'required|date',       // Ngày khởi hành chốt
            'tour_name' => 'required|string',          // Tên tour chốt
        ]);

        DB::beginTransaction();
        try {
            // 1. Xử lý User (Tìm theo email hoặc tạo mới)
            $user = User::where('email', $groupRequest->email)->first();
            
            if (!$user) {
                // Nếu chưa có tài khoản -> Tạo mới
                $tempPassword = Str::random(8); // Mật khẩu ngẫu nhiên
                $user = User::create([
                    'name' => $groupRequest->name,
                    'email' => $groupRequest->email,
                    'phone' => $groupRequest->phone,
                    'password' => Hash::make($tempPassword),
                    // 'role' => 'customer' (Nếu hệ thống bạn có cột role mặc định)
                ]);
                
                // Note: Thực tế nên gửi email báo mật khẩu cho khách ở đây
            }
            
            // Cập nhật lại user_id cho request (để sau này đối chiếu)
            $groupRequest->update(['user_id' => $user->id]);

            // 2. Tạo Tour Riêng (Private Tour)
            // Tour này status = inactive để không hiện lên trang chủ
            $tour = Tour::create([
                'title' => $request->tour_name, // Tên tour admin nhập
                'description' => "Tour đoàn thiết kế riêng.\nLịch trình: {$groupRequest->duration}.\nNhu cầu: {$groupRequest->special_requests}",
                'category_id' => 1, // ID danh mục mặc định (VD: Du lịch trong nước - Bạn cần chắc chắn ID 1 tồn tại)
                'price' => $request->final_price, // Lưu giá tổng vào đây
                'duration_days' => (int)filter_var($groupRequest->duration, FILTER_SANITIZE_NUMBER_INT) ?: 1,
                'status' => 'inactive', // ẨN KHỎI TRANG CHỦ
                'availability_status' => 'contact',
            ]);

            // 3. Tạo Lịch Khởi Hành (Departure)
            $totalPax = $groupRequest->adults + $groupRequest->children;
            $departure = TourDeparture::create([
                'tour_id' => $tour->id,
                'departure_date' => $request->departure_date,
                'seats_total' => $totalPax,
                'seats_available' => 0, // Set 0 để không ai đặt thêm được
                'price' => $request->final_price, 
                'status' => 'contact',
            ]);

            // 4. Tạo Booking (Đơn hàng)
            $booking = Booking::create([
                'user_id' => $user->id,
                'tour_id' => $tour->id,
                'departure_id' => $departure->id,
                'booking_date' => now(),
                'adults' => $groupRequest->adults,
                'children' => $groupRequest->children,
                'infants' => $groupRequest->infants,
                'total_amount' => $request->final_price, // Giá chốt cuối cùng
                'status' => 'confirmed', // Đã xác nhận (để khách có thể thanh toán ngay)
                'note' => 'Booking tạo tự động từ Yêu cầu Tour đoàn #' . $groupRequest->id,
            ]);

            // 5. Cập nhật trạng thái Yêu cầu
            $groupRequest->update([
                'status' => 'contracted', // Đã chốt
                'admin_notes' => $groupRequest->admin_notes . "\n[" . now()->format('d/m/Y H:i') . "] Đã tạo Booking #{$booking->id}. Giá chốt: " . number_format($request->final_price) . "đ"
            ]);

            DB::commit();

            return back()->with('success', 'Đã tạo Booking thành công! Mã đơn hàng: #' . $booking->id);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage()); // Ghi log lỗi
            return back()->with('error', 'Lỗi khi tạo booking: ' . $e->getMessage());
        }
    }
}