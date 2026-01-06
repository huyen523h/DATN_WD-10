<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\TourSchedule;
use App\Models\TourDeparture;
use App\Models\User;

class TourScheduleController extends Controller
{
    public function index($tourId)
    {
        $tour = Tour::findOrFail($tourId);
        $schedules = TourSchedule::where('tour_id', $tourId)
            ->with(['departure', 'guide'])
            ->orderBy('day_number')
            ->get();

        // Lấy danh sách departures và guides để hiển thị trong form
        $departures = TourDeparture::where('tour_id', $tourId)->get();
        $guides = User::whereHas('roles', function($q) {
                $q->where('name', 'guide');
            })
            ->get();

        return view('admin.tour_schedules.index', compact('tour', 'schedules', 'departures', 'guides'));
    }

    public function create($tourId)
    {
        $tour = Tour::findOrFail($tourId);
        
        // Lấy danh sách departures và guides
        $departures = TourDeparture::where('tour_id', $tourId)->get();
        $guides = User::whereHas('roles', function($q) {
                $q->where('name', 'guide');
            })
            ->get();

        return view('admin.tour_schedules.create', compact('tour', 'departures', 'guides'));
    }

    public function store(Request $request, $tourId)
    {
        $request->validate([
            'day_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
            'start_time' => 'nullable|date_format:H:i',
            'departure_id' => 'nullable|exists:tour_departures,id',
            'guide_id' => 'nullable|exists:users,id',
        ]);

        TourSchedule::create([
            'tour_id' => $tourId,
            'day_number' => $request->day_number,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'start_time' => $request->start_time,
            'departure_id' => $request->departure_id,
            'guide_id' => $request->guide_id,
        ]);

        return redirect()->route('admin.schedules.index', $tourId)
            ->with('success', 'Thêm lịch trình thành công.');
    }

    public function edit($tourId, $scheduleId)
    {
        $tour = Tour::findOrFail($tourId);
        $schedule = TourSchedule::where('tour_id', $tourId)->where('id', $scheduleId)->firstOrFail();
        
        // Lấy danh sách departures và guides
        $departures = TourDeparture::where('tour_id', $tourId)->get();
        $guides = User::whereHas('roles', function($q) {
                $q->where('name', 'guide');
            })
            ->get();

        return view('admin.tour_schedules.edit', compact('tour', 'schedule', 'departures', 'guides'));
    }

    public function update(Request $request, $tourId, $scheduleId)
    {
        $tour = Tour::findOrFail($tourId);
        $schedule = TourSchedule::where('tour_id', $tourId)->where('id', $scheduleId)->firstOrFail();

        $request->validate([
            'day_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
            'start_time' => 'nullable|date_format:H:i',
            'departure_id' => 'nullable|exists:tour_departures,id',
            'guide_id' => 'nullable|exists:users,id',
        ]);

        $schedule->update($request->only([
            'day_number', 
            'title', 
            'description', 
            'location', 
            'start_time', 
            'departure_id', 
            'guide_id'
        ]));

        return redirect()->route('admin.schedules.index', $tourId)
            ->with('success', 'Cập nhật lịch trình thành công.');
    }

    public function destroy($tourId, $scheduleId)
    {
        $tour = Tour::findOrFail($tourId);
        $schedule = TourSchedule::where('tour_id', $tourId)->where('id', $scheduleId)->firstOrFail();
        $schedule->delete();

        return redirect()->route('admin.schedules.index', $tourId)
            ->with('success', 'Xóa lịch trình thành công.');
    }
}
