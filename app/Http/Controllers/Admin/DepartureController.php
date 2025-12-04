<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\TourDeparture;
use Illuminate\Support\Facades\Storage;
class DepartureController extends Controller
{

    // Hiển thị danh sách khởi hành
    public function index()
    {
        $departures = TourDeparture::with('tour')->orderByDesc('id')->paginate(10);
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

        $departure->update($request->all());

        return redirect()->route('admin.departures.index')
            ->with('success', 'Cập nhật khởi hành thành công!');
    }

    // Xóa khởi hành
    public function destroy($id)
    {
        TourDeparture::findOrFail($id)->delete();
        return redirect()->route('admin.departures.index')->with('success', 'Xóa khởi hành thành công!');
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
