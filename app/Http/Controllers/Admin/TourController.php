<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tours = Tour::all();
        return view('admin.tours.index', compact('tours'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tours.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'location' => 'nullable|string|max:200',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'departure_date' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Thêm các trường khác nếu cần: duration, available_seats, category_id
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('tours/images', 'public');
            $validated['image'] = $path;
        }

        Tour::create($validated);

        return redirect()->route('admin.tours.index')->with('success', 'Tour đã được tạo.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tour $tour)
    {
        return view('admin.tours.show', compact('tour'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tour $tour)
    {
        return view('admin.tours.edit', compact('tour'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tour $tour)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'location' => 'nullable|string|max:200',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'departure_date' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Xóa hình cũ
            if ($tour->image) {
                Storage::disk('public')->delete($tour->image);
            }
            $path = $request->file('image')->store('tours/images', 'public');
            $validated['image'] = $path;
        }

        $tour->update($validated);

        return redirect()->route('admin.tours.index')->with('success', 'Tour đã được cập nhật.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tour $tour)
    {
        if ($tour->image) {
            Storage::disk('public')->delete($tour->image);
        }
        $tour->delete();

        return redirect()->route('admin.tours.index')->with('success', 'Tour đã được xóa.');
    }
}