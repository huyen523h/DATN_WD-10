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

        // Stats for dashboard
        $stats = [
            'total_tours' => Tour::count(),
            'active_tours' => Tour::where('status', 'active')->count(),
            'total_bookings' => \App\Models\Booking::count(),
            'total_departures' => \App\Models\TourDeparture::count(),
        ];

        return view('admin.tours.index', compact('tours', 'categories', 'stats'));
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
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'duration_days' => 'required|integer|min:1',
            'duration_nights' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive,draft',
            'availability_status' => 'nullable|in:available,contact,sold_out',
            'price_adult' => 'nullable|numeric|min:0',
            'price_child' => 'nullable|numeric|min:0',
            'price_infant' => 'nullable|numeric|min:0',
            'includes' => 'nullable|string',
            'excludes' => 'nullable|string',
            'surcharges' => 'nullable|string',
            'notes' => 'nullable|string',
            'cancellation_policy' => 'nullable|string',
            'visa_requirements' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'apply_same_price' => 'nullable|boolean',
            'schedule_day.*' => 'nullable|integer|min:1',
            'schedule_title.*' => 'nullable|string|max:255',
            'schedule_description.*' => 'nullable|string',
        ]);

        $durationText = $validated['duration_days'] ?? null
            ? ($validated['duration_days'] . ' ngày' . (isset($validated['duration_nights']) ? ' ' . $validated['duration_nights'] . ' đêm' : ''))
            : null;

        $coverPath = $request->file('cover_image')
            ? $request->file('cover_image')->store('tours/images', 'public')
            : null;

        $tourData = collect($validated)
            ->except([
                'cover_image',
                'images',
                'images.*',
                'schedule_day',
                'schedule_title',
                'schedule_description',
                'apply_same_price',
            ])
            ->toArray();

        $tourData['duration'] = $durationText;
        $tourData['availability_status'] = $validated['availability_status'] ?? 'available';
        if ($coverPath) {
            $tourData['image'] = $coverPath;
        }

        $tour = Tour::create($tourData);

        // Handle multiple images
        if ($coverPath) {
            $tour->images()->create([
                'image_url' => $coverPath,
                'is_cover' => true,
                'sort_order' => 1,
            ]);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('tours/images', 'public');
                $tour->images()->create([
                    'image_url' => $path,
                    'is_cover' => !$coverPath && $index === 0,
                    'sort_order' => $index + 1 + ($coverPath ? 1 : 0),
                ]);
            }
        }

        // Lưu lịch trình tour
        $days = $request->input('schedule_day', []);
        $titles = $request->input('schedule_title', []);
        $descriptions = $request->input('schedule_description', []);
        
        \Log::info('Saving tour schedules', [
            'tour_id' => $tour->id,
            'days_count' => count($days),
            'titles_count' => count($titles),
            'descriptions_count' => count($descriptions),
            'days' => $days,
            'titles' => $titles,
            'descriptions' => $descriptions,
            'duration_days' => $validated['duration_days'] ?? null,
        ]);
        
        // Lưu tất cả các ngày có trong form
        $maxCount = max(count($days), count($titles), count($descriptions));
        
        // Nếu không có schedule nào từ form nhưng tour có số ngày, tạo schedule mặc định
        if ($maxCount === 0 && isset($validated['duration_days']) && $validated['duration_days'] > 0) {
            $durationDays = (int) $validated['duration_days'];
            for ($day = 1; $day <= $durationDays; $day++) {
                $tour->schedules()->create([
                    'day_number' => $day,
                    'title' => 'Ngày ' . $day,
                    'description' => null,
                ]);
            }
            \Log::info('Created default schedules for tour', [
                'tour_id' => $tour->id,
                'days_created' => $durationDays,
            ]);
        } else {
            // Lưu các schedule từ form
            for ($idx = 0; $idx < $maxCount; $idx++) {
                $dayNumber = $days[$idx] ?? ($idx + 1);
                $title = trim($titles[$idx] ?? '');
                $description = trim($descriptions[$idx] ?? '');
                
                // Lưu nếu có ít nhất title hoặc description
                if ($title || $description) {
                    $schedule = $tour->schedules()->create([
                        'day_number' => $dayNumber,
                        'title' => $title ?: 'Ngày ' . $dayNumber,
                        'description' => $description ?: null,
                    ]);
                    \Log::info('Created schedule', [
                        'schedule_id' => $schedule->id,
                        'day_number' => $schedule->day_number,
                        'title' => $schedule->title,
                    ]);
                }
            }
        }

        return redirect()->route('admin.tours.index')->with([
            'success' => 'Tour đã được tạo thành công. Vui lòng tạo lịch khởi hành để mở bán tour.',
            'tour_id' => $tour->id,
            'show_create_departure_cta' => true,
        ]);
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

 public function update(Request $request, Tour $tour)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'location' => 'nullable|string|max:200',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'duration_days' => 'required|integer|min:1',
            'duration_nights' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive,draft',
            'availability_status' => 'nullable|in:available,contact,sold_out',
            'price_adult' => 'nullable|numeric|min:0',
            'price_child' => 'nullable|numeric|min:0',
            'price_infant' => 'nullable|numeric|min:0',
            'includes' => 'nullable|string',
            'excludes' => 'nullable|string',
            'surcharges' => 'nullable|string',
            'notes' => 'nullable|string',
            'cancellation_policy' => 'nullable|string',
            'visa_requirements' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);
        $durationText = $validated['duration_days'] ?? null
            ? ($validated['duration_days'] . ' ngày' . (isset($validated['duration_nights']) ? ' ' . $validated['duration_nights'] . ' đêm' : ''))
            : null;

        $coverPath = $request->file('cover_image')
            ? $request->file('cover_image')->store('tours/images', 'public')
            : null;

        $tourData = collect($validated)
            ->except(['cover_image', 'images', 'images.*'])
            ->toArray();
        $tourData['duration'] = $durationText;
        if (!isset($tourData['availability_status'])) {
            $tourData['availability_status'] = $tour->availability_status ?? 'available';
        }
        if ($coverPath) {
            $tourData['image'] = $coverPath;
        }
        $tour->update($tourData);

        if ($coverPath) {
            $tour->images()->create([
                'image_url' => $coverPath,
                'is_cover' => true,
                'sort_order' => ($tour->images()->max('sort_order') ?? 0) + 1,
            ]);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('tours/images', 'public');
                $tour->images()->create([
                    'image_url' => $path,
                    'is_cover' => false,
                    'sort_order' => ($tour->images()->max('sort_order') ?? 0) + $index + 1
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