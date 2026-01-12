<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TourController extends Controller
{
    /**
     * Display a listing of tours.
     */
    public function index(Request $request): View
    {
        $query = Tour::with(['category', 'images', 'departures']);

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Search by title, description, or category
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('category', function($categoryQuery) use ($searchTerm) {
                      $categoryQuery->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $tours = $query->paginate(12);
        
        // Tính toán xem tour nào có tất cả departure đã qua
        $today = \Carbon\Carbon::today();
        $tours->getCollection()->transform(function($tour) use ($today) {
            $allDeparturesPast = $tour->departures->count() > 0 && 
                                 $tour->departures->every(function($departure) use ($today) {
                                     return \Carbon\Carbon::parse($departure->departure_date)->startOfDay()->lt($today);
                                 });
            $tour->all_departures_past = $allDeparturesPast;
            return $tour;
        });
        
        $categories = Category::all();

        return view('tours.index', compact('tours', 'categories'));
    }

    /**
     * Display the specified tour.
     */
    public function show(Tour $tour): View
    {
        $tour->load(['category', 'images', 'schedules', 'reviews.user']);
        
        // Lấy tất cả departures để kiểm tra xem có tất cả đã qua chưa
        $allDepartures = \App\Models\TourDeparture::where('tour_id', $tour->id)->get();
        
        // Kiểm tra xem tất cả departure đã qua chưa
        $today = \Carbon\Carbon::today();
        $allDeparturesPast = $allDepartures->count() > 0 && 
                             $allDepartures->every(function($departure) use ($today) {
                                 return \Carbon\Carbon::parse($departure->departure_date)->startOfDay()->lt($today);
                             });
        
        // Chỉ lấy các ngày khởi hành trong tương lai cho user (nếu có)
        $tour->load(['departures' => function($query) {
            $query->whereDate('departure_date', '>=', now()->toDateString())
                  ->orderBy('departure_date');
        }]);
        
        return view('tours.show', compact('tour', 'allDeparturesPast'));
    }
}
