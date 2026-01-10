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

    // 1. Validate (Không bắt deadline nữa)
    $request->validate([
      'final_price' => 'required|numeric|min:0',
    'departure_date' => 'required|date',
    'tour_name' => 'required|string',
    'contract_file' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:5120',
    'service_details' => 'required|string',
    ]);

    DB::beginTransaction();
    try {
        // ... (Code xử lý User - GIỮ NGUYÊN) ...
        $user = User::where('email', $groupRequest->email)->first();
        if (!$user) {
            $tempPassword = Str::random(8);
            $user = User::create([
                'name' => $groupRequest->name,
                'email' => $groupRequest->email,
                'phone' => $groupRequest->phone,
                'password' => Hash::make($tempPassword),
            ]);
        }
        $groupRequest->update(['user_id' => $user->id]);

        // 2. Upload File
        $contractPath = null;
        if ($request->hasFile('contract_file')) {
            $contractPath = $request->file('contract_file')->store('contracts', 'public');
        }

        // ... (Code tạo Tour & Departure - GIỮ NGUYÊN) ...
        $tour = Tour::create([
            'title' => $request->tour_name,
            'description' => "Tour đoàn thiết kế riêng.\nLịch trình: {$groupRequest->duration}",
            'category_id' => 1,
            'price' => $request->final_price,
            'duration_days' => 1,
            'status' => 'inactive',
            'availability_status' => 'contact',
        ]);

        $departure = TourDeparture::create([
            'tour_id' => $tour->id,
            'departure_date' => $request->departure_date,
            'seats_total' => $groupRequest->adults + $groupRequest->children,
            'seats_available' => 0,
            'price' => $request->final_price,
            'status' => 'contact',
        ]);

        // 3. Tạo Booking (Đã thêm 2 trường mới)
        $booking = Booking::create([
            'user_id' => $user->id,
            'tour_id' => $tour->id,
            'departure_id' => $departure->id,
            'booking_date' => now(),
            'adults' => $groupRequest->adults,
            'children' => $groupRequest->children,
            'infants' => $groupRequest->infants,
            'total_amount' => $request->final_price,
            'status' => 'confirmed',
            'note' => 'Booking tour đoàn #' . $groupRequest->id,
            
            // LƯU 2 TRƯỜNG MỚI
            'contract_file' => $contractPath,       
            'service_details' => $request->service_details,
        ]);

        // ... (Update Request - GIỮ NGUYÊN) ...
        $groupRequest->update([
            'status' => 'contracted',
            'booking_id' => $booking->id,
        ]);

        DB::commit();
        return back()->with('success', 'Đã tạo Booking thành công!');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error($e->getMessage());
        return back()->with('error', 'Lỗi: ' . $e->getMessage());
    }
}
}