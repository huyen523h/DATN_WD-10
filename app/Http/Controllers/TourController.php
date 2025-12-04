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
        $categories = Category::all();

        return view('tours.index', compact('tours', 'categories'));
    }


    public function show(Tour $tour): View
    {
 
        $tour->load(['category', 'images', 'schedules', 'departures']);
    
        $tour->load([
            'publicReviews' => function ($query) {
                $query->with(['user', 'replies.user']); 
            }
        ]);
        
        return view('tours.show', compact('tour'));

        $tour = Tour::with(['category', 'images', 'schedules', 'departures', 'publicReviews.user'])->findOrFail($id);

        // Đếm view
        $tour->increment('views');

        // Lấy các tour liên quan (chỉ lấy tour active)
        $relatedTours = Tour::where('category_id', $tour->category_id)
            ->where('id', '!=', $tour->id)
            ->where('status', 'active') // Tour liên quan thì chỉ hiện tour công khai
            ->limit(3)
            ->get();

        return view('tours.show', compact('tour', 'relatedTours'));
    }
}
