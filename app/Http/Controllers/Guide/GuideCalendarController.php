<?php

namespace App\Http\Controllers\Guide;

use App\Http\Controllers\Controller;
use App\Models\TourDeparture;
use Illuminate\Support\Facades\Auth;

class GuideCalendarController extends Controller
{
    public function index()
    {
        $guide = Auth::user();

        // Lấy tất cả tour của HDV (chuẩn bị cho lịch)
        $departures = TourDeparture::where('guide_id', $guide->id)
            ->orderBy('departure_date')
            ->get();

        return view('guide.calendar.index', compact('departures'));
    }
}
