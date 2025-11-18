<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Tour;
use App\Models\TourDeparture;
use App\Services\NotificationService;
use Illuminate\Http\Request;

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
        $oldDate = $departure->departure_date->format('d/m/Y');

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

        // Gửi thông báo nếu ngày khởi hành thay đổi và có booking
        if ($oldDate !== \Carbon\Carbon::parse($request->departure_date)->format('d/m/Y')) {
            $newDate = \Carbon\Carbon::parse($request->departure_date)->format('d/m/Y');
            $bookings = Booking::where('departure_id', $departure->id)
                ->where('status', '!=', 'cancelled')
                ->with(['user', 'tour'])
                ->get();

            $notificationService = new NotificationService();
            foreach ($bookings as $booking) {
                $notificationService->notifyTourScheduleChanged($booking, $oldDate, $newDate);
            }
        }

        return redirect()->route('admin.departures.index')
            ->with('success', 'Cập nhật khởi hành thành công!');
    }

    // Xóa khởi hành
    public function destroy($id)
    {
        TourDeparture::findOrFail($id)->delete();
        return redirect()->route('admin.departures.index')->with('success', 'Xóa khởi hành thành công!');
    }
}
