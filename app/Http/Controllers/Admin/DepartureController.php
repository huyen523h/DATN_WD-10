<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\TourDeparture;

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
}
