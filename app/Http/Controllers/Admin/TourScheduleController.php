<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\TourSchedule;

class TourScheduleController extends Controller
{
    public function index($tourId)
    {
        $tour = Tour::findOrFail($tourId);
        $schedules = TourSchedule::where('tour_id', $tourId)
            ->orderBy('day_number')
            ->get();

        return view('admin.tour_schedules.index', compact('tour', 'schedules'));
    }

    public function create($tourId)
    {
        $tour = Tour::findOrFail($tourId);
        return view('admin.tour_schedules.create', compact('tour'));
    }

    public function store(Request $request, $tourId)
    {
        $request->validate([
            'day_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        TourSchedule::create([
            'tour_id' => $tourId,
            'day_number' => $request->day_number,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.schedules.index', $tourId)
            ->with('success', 'Thêm lịch trình thành công.');
    }

    public function edit($id)
    {
        $schedule = TourSchedule::findOrFail($id);
        $tour = $schedule->tour; // Lấy tour liên quan
        return view('admin.tour_schedules.edit', compact('tour', 'schedule'));
    }

    public function update(Request $request, $id)
    {
        $schedule = TourSchedule::findOrFail($id);

        $request->validate([
            'day_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $schedule->update($request->only(['day_number', 'title', 'description']));

        return redirect()->route('admin.schedules.index', $schedule->tour_id)
            ->with('success', 'Cập nhật lịch trình thành công.');
    }

    public function destroy($id)
    {
        $schedule = TourSchedule::findOrFail($id);
        $tourId = $schedule->tour_id;
        $schedule->delete();

        return redirect()->route('admin.schedules.index', $tourId)
            ->with('success', 'Xóa lịch trình thành công.');
    }
}
