<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Tour::with(['category', 'images', 'bookings']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tours = $query->orderBy('created_at', 'desc')->paginate(10);
        $categories = Category::all();

        return view('admin.tours.index', compact('tours', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.tours.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'location' => 'nullable|string|max:200',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'duration_days' => 'nullable|integer|min:1',
            'duration_nights' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive,draft',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $tour = Tour::create($validated);

        // Handle multiple images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('tours/images', 'public');
                $tour->images()->create([
                    'image_url' => $path,
                    'is_cover' => $index === 0, // First image is cover
                    'sort_order' => $index + 1
                ]);
            }
        }

        return redirect()->route('admin.tours.index')->with('success', 'Tour đã được tạo thành công!');
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
        $categories = Category::all();
        return view('admin.tours.edit', compact('tour', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tour $tour)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'location' => 'nullable|string|max:200',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'duration_days' => 'nullable|integer|min:1',
            'duration_nights' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive,draft',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $tour->update($validated);

        // Handle new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('tours/images', 'public');
                $tour->images()->create([
                    'image_url' => $path,
                    'is_cover' => false,
                    'sort_order' => $tour->images()->count() + $index + 1
                ]);
            }
        }

        return redirect()->route('admin.tours.index')->with('success', 'Tour đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tour $tour)
    {
        // Delete associated images
        foreach ($tour->images as $image) {
            Storage::disk('public')->delete($image->image_url);
        }
        
        $tour->delete();

        return redirect()->route('admin.tours.index')->with('success', 'Tour đã được xóa thành công!');
    }

    /**
     * Delete tour image
     */
    public function deleteImage(Tour $tour, $imageId)
    {
        try {
            // Find the image by ID
            $image = $tour->images()->findOrFail($imageId);
            
            // Delete file from storage
            if ($image->image_url && Storage::disk('public')->exists($image->image_url)) {
                Storage::disk('public')->delete($image->image_url);
            }
            
            // If this was a cover image, set another image as cover
            if ($image->is_cover) {
                $otherImage = $tour->images()->where('id', '!=', $image->id)->first();
                if ($otherImage) {
                    $otherImage->update(['is_cover' => true]);
                }
            }
            
            // Delete image record
            $image->delete();

            return redirect()->route('admin.tours.edit', $tour)->with('success', 'Hình ảnh đã được xóa thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.tours.edit', $tour)->with('error', 'Có lỗi xảy ra khi xóa hình ảnh: ' . $e->getMessage());
        }
    }
}