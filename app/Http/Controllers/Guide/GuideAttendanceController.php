<?php
namespace App\Http\Controllers\Guide;

use App\Http\Controllers\Controller;
use App\Models\GuideAttendance;
use App\Models\TourDeparture;
use Illuminate\Http\Request;

class GuideAttendanceController extends Controller
{
    public function index(TourDeparture $departure)
    {
        $guide = auth()->user();

        // Các ngày trong tour
        $dates = collect();
        $start = $departure->departure_date;
        $end   = $departure->departure_date->copy()->addDays(
            $departure->tour->duration_days - 1
        );

        while ($start <= $end) {
            $dates->push($start->copy());
            $start->addDay();
        }

        $attendances = GuideAttendance::where('departure_id', $departure->id)
            ->where('guide_id', $guide->id)
            ->get()
            ->keyBy(fn($a) => $a->work_date->format('Y-m-d'));

        return view('guide.attendance.index', compact(
            'departure',
            'dates',
            'attendances'
        ));
    }

    public function store(Request $request, TourDeparture $departure)
    {
        $guide = auth()->user();

        foreach ($request->attendance as $date => $data) {
            GuideAttendance::updateOrCreate(
                [
                    'guide_id' => $guide->id,
                    'departure_id' => $departure->id,
                    'work_date' => $date,
                ],
                [
                    'status' => $data['status'],
                    'base_salary' => 500000, // ví dụ
                    'bonus' => $data['bonus'] ?? 0,
                    'penalty' => $data['penalty'] ?? 0,
                    'total_salary' =>
                        500000 + ($data['bonus'] ?? 0) - ($data['penalty'] ?? 0),
                ]
            );
        }

        return back()->with('success', 'Đã lưu chấm công');
    }
}
