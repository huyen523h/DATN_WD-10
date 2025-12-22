<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Tour;
use App\Models\Booking;
use App\Models\Banner;
use App\Models\User;
use App\Models\Category;
use App\Models\Promotion;
use App\Models\TourImage;
use App\Models\TourSchedule;
use App\Models\TourDeparture;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\BannerRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Tính số ngày của tour dựa trên duration_days hoặc số ngày trong lịch trình.
     * Luôn trả về ít nhất 1 ngày.
     */
    protected function getTourDurationDays(?Tour $tour): int
    {
        if (!$tour) {
            // Fallback: giả định tour tối thiểu 3 ngày nếu không có dữ liệu
            return 3;
        }

        if (!empty($tour->duration_days) && (int)$tour->duration_days > 0) {
            return (int)$tour->duration_days;
        }

        // fallback: lấy max day_number trong schedules nếu có
        try {
            $maxDay = $tour->schedules()->max('day_number');
            return $maxDay ? (int)$maxDay : 3;
        } catch (\Throwable $e) {
            return 3;
        }
    }
    public function dashboard()
    {
        $stats = [
            'total_tours' => Tour::count(),
            'total_bookings' => Booking::count(),
            'total_customers' => User::whereHas('roles', function ($query) {
                $query->where('name', 'customer');
            })->count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
        ];

        $recent_bookings = Booking::with(['tour', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_bookings'));
    }

    public function tours(Request $request)
    {
        $completedStatuses = ['paid', 'completed', 'finished', 'confirmed'];

        $query = Tour::with(['category', 'images', 'bookings'])
            ->withCount([
                'bookings as completed_bookings_count' => function ($q) use ($completedStatuses) {
                    $q->whereIn('status', $completedStatuses);
                },
            ]);

        // Tìm kiếm theo tiêu đề/mô tả/danh mục
        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas(
                        'category',
                        fn($cq) =>
                        $cq->where('name', 'like', "%{$search}%")
                    );
            });
        }

        // Lọc theo danh mục
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }


        // Lọc theo TÌNH TRẠNG CHỖ (tour-level)
        $av = $request->get('availability_status'); // lấy từ query string
        if (in_array($av, ['available', 'contact', 'sold_out'], true)) {
            $query->where('availability_status', $av);
        }

        $tours = $query->orderByDesc('created_at')->paginate(10)
            ->appends($request->only(['search', 'category_id', 'availability_status']));
        $categories = Category::orderBy('name')->get();

        // Trả kèm giá trị filter hiện tại để giữ selected trong view
        return view('admin.tours.index', [
            'tours' => $tours,
            'categories' => $categories,
            'availabilityCurrent' => $av,
        ]);
    }

    /**
     * ============================
     * BANNERS MANAGEMENT
     * ============================
     */

    /**
     * Danh sách banner.
     */
    public function banners(): View
    {
        $banners = Banner::ordered()->paginate(10);

        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Form tạo banner.
     */
    public function createBanner(): View
    {
        return view('admin.banners.create');
    }

    /**
     * Lưu banner mới.
     */
    public function storeBanner(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'link_url' => 'nullable|url|max:500',
            'type' => 'required|in:hero,promotion,category,featured',
            'position' => 'required|in:top,middle,bottom,sidebar',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $path = $request->file('image')->store('banners', 'public');
        $imageUrl = Storage::url($path); // /storage/banners/...

        Banner::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'image_url' => $imageUrl,
            'link_url' => $validated['link_url'] ?? null,
            'type' => $validated['type'],
            'position' => $validated['position'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'target_audience' => ['all'],
            'click_count' => 0,
            'view_count' => 0,
        ]);

        return redirect()
            ->route('admin.banners')
            ->with('success', 'Banner đã được tạo thành công!');
    }

    /**
     * Chi tiết banner.
     */
    public function showBanner(Banner $banner): View
    {
        return view('admin.banners.show', compact('banner'));
    }

    /**
     * Form chỉnh sửa banner.
     */
    public function editBanner(Banner $banner): View
    {
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Cập nhật banner.
     */
    public function updateBanner(Request $request, Banner $banner): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'link_url' => 'nullable|url|max:500',
            'type' => 'required|in:hero,promotion,category,featured',
            'position' => 'required|in:top,middle,bottom,sidebar',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Nếu có ảnh mới thì xoá ảnh cũ và lưu ảnh mới
        if ($request->hasFile('image')) {
            if ($banner->image_url) {
                $oldPath = str_replace('/storage/', '', $banner->image_url);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('image')->store('banners', 'public');
            $banner->image_url = Storage::url($path);
        }

        $banner->title = $validated['title'];
        $banner->description = $validated['description'] ?? null;
        $banner->link_url = $validated['link_url'] ?? null;
        $banner->type = $validated['type'];
        $banner->position = $validated['position'];
        $banner->sort_order = $validated['sort_order'] ?? 0;
        $banner->is_active = $request->has('is_active');
        $banner->start_date = $validated['start_date'] ?? null;
        $banner->end_date = $validated['end_date'] ?? null;

        $banner->save();

        return redirect()
            ->route('admin.banners')
            ->with('success', 'Banner đã được cập nhật thành công!');
    }

    /**
     * Xoá banner.
     */
    public function deleteBanner(Banner $banner): RedirectResponse
    {
        if ($banner->image_url) {
            $oldPath = str_replace('/storage/', '', $banner->image_url);
            Storage::disk('public')->delete($oldPath);
        }

        $banner->delete();

        return redirect()
            ->route('admin.banners')
            ->with('success', 'Banner đã được xóa thành công!');
    }

    /**
     * Thay đổi thứ tự banner (lên / xuống).
     */
    public function moveBanner(Request $request, Banner $banner)
    {
        $direction = $request->input('direction');

        if (!in_array($direction, ['up', 'down'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Hướng di chuyển không hợp lệ.',
            ], 422);
        }

        $query = Banner::where('position', $banner->position);

        if ($direction === 'up') {
            $neighbor = $query
                ->where('sort_order', '<', $banner->sort_order)
                ->orderBy('sort_order', 'desc')
                ->first();
        } else {
            $neighbor = $query
                ->where('sort_order', '>', $banner->sort_order)
                ->orderBy('sort_order', 'asc')
                ->first();
        }

        if (!$neighbor) {
            return response()->json(['success' => true]);
        }

        DB::transaction(function () use ($banner, $neighbor) {
            $currentOrder = $banner->sort_order;
            $banner->sort_order = $neighbor->sort_order;
            $neighbor->sort_order = $currentOrder;

            $banner->save();
            $neighbor->save();
        });

        return response()->json(['success' => true]);
    }

    public function createTour(): View
    {
        $categories = Category::all();
        return view('admin.tours.create', compact('categories'));
    }

    public function storeTour(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'duration' => 'nullable|string|max:50',
            'duration_days' => 'nullable|integer|min:1|max:60',
            'nights' => 'nullable|integer|min:0|max:59',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'price_adult' => 'nullable|numeric|min:0',
            'price_child' => 'nullable|numeric|min:0',
            'price_infant' => 'nullable|numeric|min:0',
            'includes' => 'nullable|string',
            'excludes' => 'nullable|string',
            'surcharges' => 'nullable|string',
            'notes' => 'nullable|string',
            'cancellation_policy' => 'nullable|string',
            'visa_requirements' => 'nullable|string',
            // CHỈ CÒN availability_status
            'availability_status' => 'nullable|in:available,contact,sold_out',

            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'schedule_day_number.*' => 'nullable|integer|min:1|max:60',
            'schedule_title.*' => 'nullable|string|max:255',
            'schedule_description.*' => 'nullable|string',
            'departure_date.*' => 'nullable|date|after:today',
            'seats_total.*' => 'nullable|integer|min:1|max:100',
            'seats_available.*' => 'nullable|integer|min:0|max:100',
            'price_dep.*' => 'nullable|numeric|min:0',
            'child_price.*' => 'nullable|numeric|min:0',
            'infant_price.*' => 'nullable|numeric|min:0',
            'status_dep.*' => 'nullable|in:available,contact,sold_out',
        ]);

        $tour = \App\Models\Tour::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'duration' => $validated['duration'] ?? null,
            'duration_days' => $validated['duration_days'] ?? null,
            'nights' => $validated['nights'] ?? null,
            'price' => $validated['price'],
            'original_price' => $validated['original_price'] ?? null,
            'discount_price' => $validated['discount_price'] ?? null,
            'price_adult' => $validated['price_adult'] ?? null,
            'price_child' => $validated['price_child'] ?? null,
            'price_infant' => $validated['price_infant'] ?? null,
            'includes' => $validated['includes'] ?? null,
            'excludes' => $validated['excludes'] ?? null,
            'surcharges' => $validated['surcharges'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'cancellation_policy' => $validated['cancellation_policy'] ?? null,
            'visa_requirements' => $validated['visa_requirements'] ?? null,
            'availability_status' => $validated['availability_status'] ?? 'available',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $image) {
                $path = $image->store('tours', 'public');
                \App\Models\TourImage::create([
                    'tour_id' => $tour->id,
                    'image_url' => \Illuminate\Support\Facades\Storage::url($path),
                    'is_cover' => $i === 0,
                    'sort_order' => $i + 1,
                ]);
            }
        }

        if ($request->has('schedule_day_number')) {
            foreach ($request->schedule_day_number as $i => $dayNum) {
                if (!empty($request->schedule_title[$i])) {
                    \App\Models\TourSchedule::create([
                        'tour_id' => $tour->id,
                        'day_number' => $dayNum,
                        'title' => $request->schedule_title[$i],
                        'description' => $request->schedule_description[$i] ?? '',
                    ]);
                }
            }
        }

        if ($request->has('departure_date')) {
            foreach ($request->departure_date as $i => $date) {
                if (!empty($date)) {
                    \App\Models\TourDeparture::create([
                        'tour_id' => $tour->id,
                        'departure_date' => $date,
                        'seats_total' => $request->seats_total[$i] ?? 20,
                        'seats_available' => $request->seats_available[$i] ?? 20,
                        'price' => $request->price_dep[$i] ?? ($validated['price_adult'] ?? $validated['price']),
                        'child_price' => $request->child_price[$i] ?? ($validated['price_child'] ?? null),
                        'infant_price' => $request->infant_price[$i] ?? ($validated['price_infant'] ?? null),
                        'status' => $request->status_dep[$i] ?? 'available',
                    ]);
                }
            }
        }

        return redirect()->route('admin.tours.index')->with('success', 'Tour đã được tạo thành công!');
    }

    public function editTour(Tour $tour): View
    {
        $tour->load(['category', 'images', 'schedules', 'departures']);
        $categories = Category::all();
        return view('admin.tours.edit', compact('tour', 'categories'));
    }

    public function showTour(Tour $tour): View
    {
        $tour->load(['category', 'images', 'schedules', 'departures', 'bookings.user', 'reviews.user']);
        return view('admin.tours.show', compact('tour'));
    }

    public function deleteTour(Tour $tour): RedirectResponse
    {
        $completedStatuses = ['paid', 'completed', 'finished', 'confirmed'];

        $hasCompletedBookings = $tour->bookings()
            ->whereIn('status', $completedStatuses)
            ->exists();

        if ($hasCompletedBookings) {
            return redirect()
                ->route('admin.tours.index')
                ->with('error', 'Tour này đã có người đặt, bạn không thể xóa.');
        }
        $tour->delete();

        return redirect()
            ->route('admin.tours.index')
            ->with('success', 'Tour đã được xóa thành công!');
    }
    public function deleteTourImage($tourId, $imageId): RedirectResponse
    {
        $image = TourImage::where('tour_id', $tourId)->findOrFail($imageId);

        // Xóa file vật lý nếu tồn tại
        if ($image->image_url) {
            $path = str_replace('/storage/', '', $image->image_url);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        // Xóa bản ghi trong DB
        $image->delete();

        return back()->with('success', 'Ảnh đã được xóa thành công!');
    }

    public function updateTour(Request $request, Tour $tour)
    {
        // 1. VALIDATE DỮ LIỆU
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'duration' => 'nullable|string|max:50',
            'duration_days' => 'nullable|integer|min:1|max:60',
            'nights' => 'nullable|integer|min:0|max:59',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'price_adult' => 'nullable|numeric|min:0',
            'price_child' => 'nullable|numeric|min:0',
            'price_infant' => 'nullable|numeric|min:0',
            'includes' => 'nullable|string',
            'excludes' => 'nullable|string',
            'surcharges' => 'nullable|string',
            'notes' => 'nullable|string',
            'cancellation_policy' => 'nullable|string',
            'visa_requirements' => 'nullable|string',
            'availability_status' => 'nullable|in:available,contact,sold_out',
            'status'              => 'required|in:active,inactive,draft',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',

            // Schedules
            'schedule_day_number.*' => 'nullable|integer|min:1|max:60',
            'schedule_title.*' => 'nullable|string|max:255',
            'schedule_description.*' => 'nullable|string',
            
            // Departures
            'departure_date.*' => 'nullable|date',
            'seats_total.*' => 'nullable|integer|min:1|max:100',
            'seats_available.*' => 'nullable|integer|min:0|max:100',
            'price_dep.*' => 'nullable|numeric|min:0',
            'child_price.*' => 'nullable|numeric|min:0',
            'infant_price.*' => 'nullable|numeric|min:0',
            'status_dep.*' => 'nullable|in:available,contact,sold_out',

            // Validate cho ô nhập tổng số chỗ
            'max_participants' => 'nullable|integer|min:1', 
        ]);

        if ($request->filled('max_participants')) {
            $newMax = (int) $request->max_participants;

            // 1. Lấy tất cả chuyến đi tương lai
            $futureDepartures = \Illuminate\Support\Facades\DB::table('tour_departures')
                ->where('tour_id', $tour->id)
                ->where('departure_date', '>=', now()->toDateString())
                ->get();

            foreach ($futureDepartures as $dep) {
                // 2. Đếm khách dựa trên departure_id (VÌ DB BẠN DÙNG CỘT NÀY)
                $bookedCount = \Illuminate\Support\Facades\DB::table('bookings')
                    ->where('departure_id', $dep->id) // <--- ĐÃ SỬA: Dùng ID thay vì Date
                    ->whereIn('status', ['paid', 'confirmed'])
                    ->get()
                    ->sum(function ($b) { return $b->adults + $b->children; });

                // 3. Nếu số mới < số đã đặt => BÁO LỖI
                if ($newMax < $bookedCount) {
                    $dateFormatted = \Carbon\Carbon::parse($dep->departure_date)->format('d/m/Y');
                    return back()->withErrors([
                        'max_participants' => "Lỗi: Không thể giảm xuống $newMax chỗ. Chuyến ngày $dateFormatted (ID: $dep->id) đã có $bookedCount khách đặt."
                    ])->withInput();
                }
            }
        }
        DB::transaction(function () use ($request, $tour, $validated) {

            // 1) Update Tour Mẹ (Loại bỏ max_participants)
            $updateData = \Illuminate\Support\Arr::except($validated, ['max_participants', 'images']);
            $tour->update($updateData);

            // 2) Xử lý ảnh
            if ($request->hasFile('images')) {
                foreach ($tour->images as $old) {
                    $path = str_replace('/storage/', '', $old->image_url);
                    Storage::disk('public')->delete($path);
                    $old->delete();
                }

                $order = 1;
                foreach ($request->file('images') as $idx => $image) {
                    if (!$image) continue;
                    $path = $image->store('tours', 'public');
                    \App\Models\TourImage::create([
                        'tour_id'    => $tour->id,
                        'image_url'  => Storage::url($path),
                        'is_cover'   => $idx === 0,         
                        'sort_order' => $order++,
                    ]);
                }
            }

            // 3) Schedules
            $tour->schedules()->delete();
            if ($request->has('schedule_day_number')) {
                foreach ($request->schedule_day_number as $i => $dayNum) {
                    if (!empty($request->schedule_title[$i])) {
                        \App\Models\TourSchedule::create([
                            'tour_id' => $tour->id,
                            'day_number' => $dayNum,
                            'title' => $request->schedule_title[$i],
                            'description' => $request->schedule_description[$i] ?? '',
                        ]);
                    }
                }
            }

            // 4) Departures (Logic cũ của bạn: Xóa đi tạo lại từ danh sách bên dưới form)
            if ($tour->status === 'active' && $request->has('departure_date')) {
                 $tour->departures()->delete();
                 
                 foreach ($request->departure_date as $i => $date) {
                    if (!empty($date)) {
                        \App\Models\TourDeparture::create([
                            'tour_id' => $tour->id,
                            'departure_date' => $date,
                            'seats_total' => $request->seats_total[$i] ?? 20,
                            'seats_available' => $request->seats_available[$i] ?? 20,
                            'price' => $request->price_dep[$i] ?? ($validated['price'] ?? 0),
                            'child_price' => $request->child_price[$i] ?? ($validated['price_child'] ?? null),
                            'infant_price' => $request->infant_price[$i] ?? ($validated['price_infant'] ?? null),
                            'status' => $request->status_dep[$i] ?? 'available',
                        ]);
                    }
                }
            }
            
            // ========================================================================
            // [PHẦN MỚI 2] CẬP NHẬT ĐỒNG BỘ (ĐÃ SỬA THEO DB BOOKINGS)
            // ========================================================================
            if ($request->filled('max_participants')) {
                $newMax = (int) $request->max_participants;
                
                // Lấy lại danh sách vừa tạo lại
                $departuresToUpdate = \App\Models\TourDeparture::where('tour_id', $tour->id)
                    ->where('departure_date', '>=', now()->toDateString())
                    ->get();

                foreach ($departuresToUpdate as $dep) {
                    // Đếm lại khách theo departure_id
                    $bookedCount = \Illuminate\Support\Facades\DB::table('bookings')
                        ->where('departure_id', $dep->id) // <--- ĐÃ SỬA TẠI ĐÂY NỮA
                        ->whereIn('status', ['paid', 'confirmed'])
                        ->get()
                        ->sum(function ($b) { return $b->adults + $b->children; });

                    // Update số chỗ mới
                    $dep->update([
                        'seats_total' => $newMax,
                        'seats_available' => $newMax - $bookedCount // Tự động trừ đi số khách đã đặt
                    ]);
        }
            }
        });

        return redirect()
            ->route('admin.tours.index')
            ->with('success', 'Tour đã được cập nhật thành công!');
    }
    public function bookings()
    {
        // Lấy tất cả bookings với relationships
        $bookings = Booking::with(['tour', 'user', 'departure.vehicle', 'departure.guide'])
            ->whereHas('departure') // Chỉ lấy bookings có departure
            ->orderBy('created_at', 'desc')
            ->get();

        // Gom booking theo NGÀY KHỞI HÀNH + TOUR
        // => Hai tour khác nhau nhưng cùng ngày sẽ được tách thành 2 nhóm riêng
        $groupedBookings = collect();
        $groupsWithDate = collect();
        $groupsWithoutDate = collect();
        
        foreach ($bookings as $booking) {
            $dateString = null;
            if ($booking->departure && $booking->departure->departure_date) {
                $dateString = $booking->departure->departure_date->format('Y-m-d');
            }
            $tourId = $booking->tour_id ?? 0;

            // Khóa nhóm: date|tour_id (vd: 2025-12-20|14)
            $key = ($dateString ?? 'no-date') . '|' . $tourId;

            if ($dateString) {
                if (!$groupsWithDate->has($key)) {
                    $groupsWithDate->put($key, collect());
                }
                $groupsWithDate->get($key)->push($booking);
            } else {
                if (!$groupsWithoutDate->has($key)) {
                    $groupsWithoutDate->put($key, collect());
                }
                $groupsWithoutDate->get($key)->push($booking);
            }
        }

        // Sắp xếp nhóm có ngày theo thứ tự tăng dần (ngày gần nhất trước)
        $groupsWithDate = $groupsWithDate->sortKeys();

        // Gộp lại: nhóm có ngày trước, nhóm không có ngày sau
        foreach ($groupsWithDate as $key => $value) {
            $groupedBookings->put($key, $value);
        }
        foreach ($groupsWithoutDate as $key => $value) {
            $groupedBookings->put($key, $value);
        }

        // Tính toán thống kê cho mỗi nhóm và sắp xếp bookings trong mỗi nhóm
        $groupedBookings = $groupedBookings->map(function ($group, $key) {
            // Tách key thành phần ngày & tour_id
            [$datePart, $tourIdPart] = explode('|', $key);

            // Sắp xếp bookings trong nhóm theo thời gian tạo (mới nhất trước)
            $sortedBookings = $group->sortByDesc('created_at')->values();
            
            // Lấy tour từ booking đầu tiên
            $firstBooking = $sortedBookings->first();
            $tour = $firstBooking->tour ?? null;
            $tourId = $tour ? $tour->id : null;
            
            // Lấy departure của tour này và cùng ngày khởi hành (đảm bảo đúng tour)
            $departure = null;
            if ($tourId && $datePart !== 'no-date') {
                $departure = TourDeparture::where('tour_id', $tourId)
                    ->whereDate('departure_date', $datePart)
                    ->first();
            } else {
                // Fallback: lấy từ booking nếu không tìm thấy
                $departure = $firstBooking->departure ?? null;
            }
            
            return [
                'date' => $datePart === 'no-date' ? null : \Carbon\Carbon::parse($datePart),
                'tour' => $tour,
                'tour_id' => $tour ? $tour->id : null,
                'bookings' => $sortedBookings,
                'total_guests' => $sortedBookings->sum(function ($booking) {
                    return $booking->adults + $booking->children + $booking->infants;
                }),
                'total_adults' => $sortedBookings->sum('adults'),
                'total_children' => $sortedBookings->sum('children'),
                'total_infants' => $sortedBookings->sum('infants'),
                'total_amount' => $sortedBookings->sum('total_amount'),
                'count' => $sortedBookings->count(),
                'departure' => $departure,
                'group_confirmed' => $departure ? $departure->group_confirmed : false,
                'confirmed_guests_count' => $departure ? $departure->confirmed_guests_count : null,
                'guide' => $departure && $departure->guide ? $departure->guide : null,
                'vehicle' => $departure && $departure->vehicle ? $departure->vehicle : null,
                'vehicle_type' => $departure ? $departure->vehicle_type : null,
                'vehicle_details' => $departure ? $departure->vehicle_details : null,
                'driver_contact' => $departure ? $departure->driver_contact : null,
            ];
        });

        // Lấy danh sách guides
        $guides = User::whereHas('roles', function($q) {
            $q->where('name', 'guide');
        })->orderBy('name')->get();

        // Lấy danh sách xe đang hoạt động để gán (status = 1)
        $vehicles = \App\Models\Vehicle::where('status', 1)
            ->orderBy('license_plate')
            ->get();

        return view('admin.bookings.index', compact('groupedBookings', 'bookings', 'guides', 'vehicles'));
    }

    public function showBooking(Booking $booking): View
    {
        // Load các quan hệ dữ liệu
        $booking->load(['tour', 'user', 'departure.guide', 'departure.vehicle', 'payment', 'documents', 'chat.messages.sender']);

        // Ngày khởi hành & tour hiện tại (nếu có)
        $departureDate = $booking->departure?->departure_date;
        $tourId        = $booking->tour_id;

        // Lấy danh sách guides (staff + guide)
        $allGuides = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['staff', 'guide']);
        })->orderBy('name')->get();

        $busyGuideIds = [];
        if ($departureDate) {
            $query = TourDeparture::whereDate('departure_date', $departureDate)
                ->whereNotNull('guide_id');

            // Loại trừ tour hiện tại để vẫn giữ HDV đã gán cho tour này
            if ($tourId) {
                $query->where('tour_id', '!=', $tourId);
            }

            $busyGuideIds = $query->pluck('guide_id')->unique()->toArray();
        }

        // Chỉ giữ các HDV chưa bận tour khác cùng ngày
        $guides = $allGuides->reject(function($guide) use ($busyGuideIds) {
            return in_array($guide->id, $busyGuideIds);
        })->values();

        // Lấy danh sách xe đang hoạt động
        $allVehicles = \App\Models\Vehicle::where('status', 1)
            ->orderBy('license_plate')
            ->get();

        $busyVehicleIds = [];
        if ($departureDate) {
            $query = TourDeparture::whereDate('departure_date', $departureDate)
                ->whereNotNull('vehicle_id');

            // Loại trừ tour hiện tại để vẫn giữ xe đã gán cho tour này
            if ($tourId) {
                $query->where('tour_id', '!=', $tourId);
            }

            $busyVehicleIds = $query->pluck('vehicle_id')->unique()->toArray();
        }

        // Chỉ giữ các xe chưa bận tour khác cùng ngày
        $vehicles = $allVehicles->reject(function($vehicle) use ($busyVehicleIds) {
            return in_array($vehicle->id, $busyVehicleIds);
        })->values();

        return view('admin.bookings.show', compact('booking', 'guides', 'vehicles'));
    }

    public function customers()
    {
        $customers = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })->with(['bookings'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);


        return view('admin.customers.index', compact('customers'));
    }

    public function showCustomer(User $user): View
    {
        $user->load(['bookings.tour', 'reviews.tour', 'supportTickets', 'notifications']);
        return view('admin.customers.show', compact('user'));
    }

    public function categories()
    {
        $categories = Category::withCount('tours')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function showCategory(Category $category): View
    {
        $category->load(['tours.images', 'tours.bookings']);
        return view('admin.categories.show', compact('category'));
    }

    public function createCategory(): View
    {
        return view('admin.categories.create');
    }

    public function reviews()
    {
        $reviews = \App\Models\Review::with(['tour', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function payments()
    {
        $payments = \App\Models\Payment::with(['booking.user', 'booking.tour'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.payments.index', compact('payments'));
    }

    public function promotions()
    {
        $promotions = Promotion::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.promotions.index', compact('promotions'));
    }

    public function showPromotion(Promotion $promotion): View
    {
        $promotion->load(['bookings.user', 'bookings.tour']);
        return view('admin.promotions.show', compact('promotion'));
    }

    public function createPromotion(): View
    {
        return view('admin.promotions.create');
    }

    public function storePromotion(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:promotions,code',
            'description' => 'nullable|string',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        if (empty($validated['discount_percent']) && empty($validated['discount_amount'])) {
            return back()->withErrors(['discount_percent' => 'Cần nhập phần trăm hoặc số tiền giảm.'])->withInput();
        }

        $validated['code'] = strtoupper($validated['code']);

        Promotion::create($validated);

        return redirect()->route('admin.promotions')->with('success', 'Mã giảm giá đã được tạo.');
    }

    public function editPromotion(Promotion $promotion): View
    {
        return view('admin.promotions.edit', compact('promotion'));
    }

    public function updatePromotion(Request $request, Promotion $promotion): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:promotions,code,' . $promotion->id,
            'description' => 'nullable|string',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        if (empty($validated['discount_percent']) && empty($validated['discount_amount'])) {
            return back()->withErrors(['discount_percent' => 'Cần nhập phần trăm hoặc số tiền giảm.'])->withInput();
        }

        $validated['code'] = strtoupper($validated['code']);

        $promotion->update($validated);

        return redirect()->route('admin.promotions')->with('success', 'Mã giảm giá đã được cập nhật.');
    }

    public function deletePromotion(Promotion $promotion): RedirectResponse
    {
        $promotion->delete();
        return redirect()->route('admin.promotions')->with('success', 'Mã giảm giá đã được xóa.');
    }

    public function reports(Request $request)
    {
        // 1. Xử lý bộ lọc ngày (Mặc định là đầu tháng đến hiện tại)
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // 2. Query dữ liệu Payment
        $query = Payment::where('status', 'completed')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        // 3. Tính Stats Cards
        $stats = [
            'total_revenue' => $query->sum('amount'),
            'monthly_revenue' => $query->sum('amount'),
            'total_bookings' => Booking::whereBetween('created_at', [$startDate, $endDate])->count(),
            'completed_bookings' => Booking::where('status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate])->count(),
        ];

        // 4. Dữ liệu cho BIỂU ĐỒ DOANH THU (Line Chart)
        $chartData = Payment::selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 5. Dữ liệu cho TOP TOUR (List)
        $topTours = Booking::select('tour_id', DB::raw('count(*) as total'))
            ->with('tour')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('tour_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('admin.reports', compact('stats', 'chartData', 'topTours', 'startDate', 'endDate'));
    }

    public function notifications()
    {
        $notifications = \App\Models\Notification::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function createNotification(): View
    {
        $users = User::all();
        return view('admin.notifications.create', compact('users'));
    }

    public function support()
    {
        $tickets = \App\Models\SupportTicket::with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.support.index', compact('tickets'));
    }

    public function createSupportTicket(): View
    {
        $users = User::all();
        return view('admin.support.create', compact('users'));
    }

    public function settings()
    {
        return view('admin.settings');
    }

    // Categories CRUD
    public function storeCategory(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'status' => 'required|in:active,inactive',
        ]);

        \App\Models\Category::create($validated);

        return redirect()->route('admin.categories')->with('success', 'Danh mục đã được tạo thành công!');
    }

    public function editCategory(\App\Models\Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, \App\Models\Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'status' => 'required|in:active,inactive',
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories')->with('success', 'Danh mục đã được cập nhật thành công!');
    }

    public function deleteCategory(\App\Models\Category $category): RedirectResponse
    {
        $category->delete();
        return redirect()->route('admin.categories')->with('success', 'Danh mục đã được xóa thành công!');
    }

    // Notifications CRUD
    public function storeNotification(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:info,success,warning,error',
            'user_id' => 'nullable|exists:users,id',
        ]);

        \App\Models\Notification::create($validated);

        return redirect()->route('admin.notifications')->with('success', 'Thông báo đã được gửi thành công!');
    }

    public function storeSupportTicket(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'user_id' => 'required|exists:users,id',
        ]);

        \App\Models\SupportTicket::create($validated);

        return redirect()->route('admin.support')->with('success', 'Ticket đã được tạo thành công!');
    }

    public function updateNotification(Request $request, \App\Models\Notification $notification): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:info,success,warning,error',
            'is_read' => 'boolean',
        ]);

        $notification->update($validated);

        return redirect()->route('admin.notifications')->with('success', 'Thông báo đã được cập nhật thành công!');
    }

    public function deleteNotification(\App\Models\Notification $notification): RedirectResponse
    {
        $notification->delete();
        return redirect()->route('admin.notifications')->with('success', 'Thông báo đã được xóa thành công!');
    }

    // Bookings CRUD
    public function updateBooking(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'notes' => 'nullable|string',
            'refund_amount' => 'nullable|numeric|min:0',
            'refund_reason' => 'nullable|string',
        ]);

        $oldStatus = $booking->status;
        $booking->update($validated);

        // Gửi thông báo hoàn tiền nếu có refund_amount
        if (!empty($validated['refund_amount']) && $validated['refund_amount'] > 0) {
            $notificationService = new \App\Services\NotificationService();
            $notificationService->notifyRefund(
                $booking,
                $validated['refund_amount'],
                $validated['refund_reason'] ?? 'Hoàn tiền theo yêu cầu'
            );
        }

        // Always return JSON if request has JSON headers
        $acceptHeader = $request->header('Accept', '');
        $contentTypeHeader = $request->header('Content-Type', '');
        $isAjax = $request->header('X-Requested-With') === 'XMLHttpRequest';

        if (
            $request->expectsJson()
            || $request->wantsJson()
            || str_contains($acceptHeader, 'application/json')
            || str_contains($contentTypeHeader, 'application/json')
            || $isAjax
        ) {
            return response()->json([
                'success' => true,
                'message' => 'Đặt tour đã được cập nhật thành công!',
                'booking' => $booking->fresh()
            ]);
        }

        return redirect()->route('admin.bookings')->with('success', 'Đặt tour đã được cập nhật thành công!');
    }

    public function deleteBooking(Request $request, Booking $booking)
    {
        $booking->delete();

        // Check if request is AJAX/JSON request
        $acceptHeader = $request->header('Accept', '');
        $contentTypeHeader = $request->header('Content-Type', '');
        $isAjax = $request->header('X-Requested-With') === 'XMLHttpRequest';

        if (
            $request->expectsJson()
            || $request->wantsJson()
            || str_contains($acceptHeader, 'application/json')
            || str_contains($contentTypeHeader, 'application/json')
            || $isAjax
        ) {
            return response()->json([
                'success' => true,
                'message' => 'Đặt tour đã được xóa thành công!'
            ]);
        }

        return redirect()->route('admin.bookings')->with('success', 'Đặt tour đã được xóa thành công!');
    }

    // Customers CRUD
    public function updateCustomer(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
        ]);

        $user->update($validated);

        return redirect()->route('admin.customers')->with('success', 'Khách hàng đã được cập nhật thành công!');
    }

    public function deleteCustomer(User $user): RedirectResponse
    {
        $user->delete();
        return redirect()->route('admin.customers')->with('success', 'Khách hàng đã được xóa thành công!');
    }

    // Reviews CRUD
    public function updateReview(Request $request, \App\Models\Review $review): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
            'status' => 'required|in:visible,hidden',
        ]);

        $review->update($validated);

        return redirect()->route('admin.reviews')->with('success', 'Đánh giá đã được cập nhật thành công!');
    }

    public function deleteReview(\App\Models\Review $review): RedirectResponse
    {
        $review->delete();
        return redirect()->route('admin.reviews')->with('success', 'Đánh giá đã được xóa thành công!');
    }

    // Payments CRUD
    public function updatePayment(Request $request, \App\Models\Payment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,completed,failed',
            'notes' => 'nullable|string',
        ]);

        $payment->update($validated);

        return redirect()->route('admin.payments')->with('success', 'Thanh toán đã được cập nhật thành công!');
    }

    public function deletePayment(\App\Models\Payment $payment): RedirectResponse
    {
        $payment->delete();
        return redirect()->route('admin.payments')->with('success', 'Thanh toán đã được xóa thành công!');
    }

    // Support CRUD
    public function updateTicket(Request $request, \App\Models\SupportTicket $ticket): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'priority' => 'required|in:low,medium,high',
            'admin_notes' => 'nullable|string',
        ]);

        $ticket->update($validated);

        return redirect()->route('admin.support')->with('success', 'Ticket đã được cập nhật thành công!');
    }

    public function deleteTicket(\App\Models\SupportTicket $ticket): RedirectResponse
    {
        $ticket->delete();
        return redirect()->route('admin.support')->with('success', 'Ticket đã được xóa thành công!');
    }

    // Settings
    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_email' => 'required|email',
            'site_phone' => 'nullable|string|max:20',
            'site_address' => 'nullable|string',
        ]);

        // Update settings in database or config
        foreach ($validated as $key => $value) {
            // This would typically update a settings table or config file
            // For now, we'll just return success
        }

        return redirect()->route('admin.settings')->with('success', 'Cài đặt đã được cập nhật thành công!');
    }
    /**
     * Xác nhận booking đơn lẻ
     */
    public function confirmBooking(Request $request, Booking $booking): RedirectResponse
    {
        $booking->update(['status' => 'confirmed']);
        
        // Gửi thông báo
        $notificationService = new NotificationService();
        $notificationService->sendNotification(
            $booking->user,
            'booking_confirmed',
            'Đặt tour đã được xác nhận',
            "Đặt tour \"{$booking->tour->title}\" của bạn đã được xác nhận. Ngày khởi hành: " . 
            ($booking->departure ? $booking->departure->departure_date->format('d/m/Y') : 'N/A'),
            $booking->id,
            'booking',
            true
        );

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Đặt tour đã được xác nhận thành công!');
    }

    /**
     * Đánh dấu đã thanh toán (tiền mặt + lưu ảnh phiếu thu)
     */
    public function markAsPaid(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        // 1. VALIDATE: Bắt buộc phải có ảnh và số tiền
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'note'   => 'nullable|string|max:500',
            'receipt_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ], [
            'receipt_image.required' => 'Bắt buộc phải chụp và upload ảnh phiếu thu làm bằng chứng!',
            'receipt_image.image'    => 'File tải lên phải là hình ảnh.',
            'receipt_image.max'      => 'Ảnh quá lớn. Vui lòng chọn ảnh dưới 5MB.',
        ]);
        
        // 2. XỬ LÝ ẢNH (ghi đè ảnh cũ nếu có)
        $imagePath = $booking->receipt_image;

        if ($request->hasFile('receipt_image')) {
            if ($booking->receipt_image && Storage::disk('public')->exists($booking->receipt_image)) {
                Storage::disk('public')->delete($booking->receipt_image);
            }

            $imagePath = $request->file('receipt_image')->store('receipts', 'public');
        }

        // 3. TẠO LỊCH SỬ THANH TOÁN (Payment Record)
        Payment::create([
            'booking_id'       => $booking->id,
            'user_id'          => Auth::id(),
            'amount'           => $request->amount,
            'payment_method'   => 'cash',
            'status'           => 'completed',
            'transaction_code' => 'CASH-' . time(),
            'note'             => $request->note ? $request->note . ' (Có ảnh phiếu thu)' : 'Thanh toán tiền mặt (có ảnh phiếu thu)',
            'payment_date'     => now(),
        ]);

        // 4. CẬP NHẬT TRẠNG THÁI ĐƠN HÀNG & LƯU ẢNH
        $booking->update([
            'status'        => 'paid',
            'receipt_image' => $imagePath,
        ]);

        return back()->with('success', 'Đã xác nhận thu tiền và lưu ảnh phiếu thu thành công!');
    }

    /**
     * Cập nhật lại ảnh biên lai thu tiền mặt
     */
    public function updateReceiptImage(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'receipt_image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'receipt_image.required' => 'Vui lòng chọn ảnh mới để cập nhật.',
            'receipt_image.image'    => 'File phải là hình ảnh.',
        ]);

        if ($request->hasFile('receipt_image')) {
            if ($booking->receipt_image && Storage::disk('public')->exists($booking->receipt_image)) {
                Storage::disk('public')->delete($booking->receipt_image);
            }

            $path = $request->file('receipt_image')->store('receipts', 'public');

            $booking->update([
                'receipt_image' => $path,
            ]);
        }

        return back()->with('success', 'Đã cập nhật lại ảnh phiếu thu mới thành công!');
    }
    /**
     * Hủy đơn hàng & xử lý hoàn tiền (nếu có)
     */
    public function cancelBooking(Request $request, Booking $booking)
    {
        $rules = [
            'cancel_reason' => 'required|string|max:1000',
        ];

        // Nếu đơn đã thanh toán (PAID), bắt buộc phải nhập số tiền hoàn và upload ảnh chứng từ
        if ($booking->status === 'paid') {
            $rules['refund_amount'] = 'required|numeric|min:0';
            $rules['refund_proof_file'] = 'required|image|max:2048';
        }

        $request->validate($rules, [
            'cancel_reason.required' => 'Vui lòng nhập lý do hủy tour.',
            'refund_proof_file.required' => 'Bắt buộc phải upload ảnh bằng chứng chuyển khoản hoàn tiền.',
        ]);

        try {
            DB::transaction(function () use ($request, $booking) {
                // A. Xử lý hoàn tiền (nếu đã thanh toán)
                if ($booking->status === 'paid') {
                    $path = null;
                    if ($request->hasFile('refund_proof_file')) {
                        $path = $request->file('refund_proof_file')->store('refunds', 'public');
                    }

                    Payment::create([
                        'booking_id'       => $booking->id,
                        'user_id'          => Auth::id(),
                        'amount'           => -1 * abs($request->refund_amount),
                        'payment_method'   => 'refund',
                        'status'           => 'completed',
                        'transaction_code' => 'REFUND_' . now()->format('YmdHis') . '_' . $booking->id,
                        'note'             => 'Hoàn tiền hủy tour. Lý do: ' . $request->cancel_reason,
                        'refund_proof'     => $path,
                        'payment_date'     => now(),
                    ]);
                }

                // B. Cập nhật trạng thái booking
                $booking->update([
                    'status' => 'cancelled',
                    'cancel_reason' => $request->cancel_reason,
                ]);

                // C. Trả lại chỗ cho departure (nếu có)
                if ($booking->departure) {
                    $seatsToRestore = $booking->adults + $booking->children;
                    $booking->departure->increment('seats_available', $seatsToRestore);
                }
            });

            return back()->with('success', 'Đã hủy đơn hàng và cập nhật sổ sách thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

 // hàm upload file tour theo đoàn 4/12/2025
    public function uploadManifest(Request $request, Booking $booking)
    {
        $request->validate([
            'manifest_file' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:5120',
        ]);

        if ($request->hasFile('manifest_file')) {
            if ($booking->passenger_manifest_file) {
                Storage::disk('public')->delete($booking->passenger_manifest_file);
            }
            $path = $request->file('manifest_file')->store('manifests', 'public');
            $booking->update(['passenger_manifest_file' => $path]);
            return back()->with('success', 'Admin đã cập nhật danh sách đoàn thành công!');
        }
        return back()->with('error', 'Lỗi upload.');
    }

    /**
     * B2: Chốt đoàn (chốt số lượng khách)
     */
    public function confirmGroup(Request $request)
    {
        $request->validate([
            'departure_date' => 'required|date',
            'confirmed_guests_count' => 'required|integer|min:1',
        ]);

        // Lấy tất cả departures có cùng ngày khởi hành
        $departures = TourDeparture::whereDate('departure_date', $request->departure_date)->get();
        
        if ($departures->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy lịch khởi hành cho ngày này.'
            ], 404);
        }

        // Cập nhật trạng thái chốt đoàn cho tất cả departures cùng ngày
        foreach ($departures as $departure) {
            $departure->update([
                'group_confirmed' => true,
                'confirmed_guests_count' => $request->confirmed_guests_count,
                'group_confirmed_at' => now(),
                'group_confirmed_by' => Auth::id(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã chốt đoàn thành công!',
            'data' => [
                'departure_date' => $request->departure_date,
                'confirmed_guests_count' => $request->confirmed_guests_count,
            ]
        ]);
    }

    /**
     * API: Lấy danh sách HDV có sẵn cho một khoảng thời gian tour (theo ngày khởi hành + số ngày tour)
     */
    public function getAvailableGuides(Request $request)
    {
        $request->validate([
            'departure_date' => 'nullable|date',
            'tour_id' => 'nullable|exists:tours,id',
        ]);

        // Lấy tất cả HDV
        $allGuides = User::whereHas('roles', function($q) {
            $q->where('name', 'guide');
        })->orderBy('name')->get();

        $busyGuideIds = [];

        // Chỉ lọc HDV bận nếu có ngày khởi hành hợp lệ
        if ($request->filled('departure_date')) {
            $startDate = \Carbon\Carbon::parse($request->departure_date)->startOfDay();

            // Tính ngày kết thúc của tour hiện tại (nếu có tour_id thì dùng duration_days)
            // Quy ước: chặn thêm 1 ngày sau khi tour kết thúc để đảm bảo HDV/xe về kịp
            $endDate = (clone $startDate);
            if ($request->filled('tour_id')) {
                $currentTour = Tour::find($request->tour_id);
                $durationDays = $this->getTourDurationDays($currentTour);
                $endDate->addDays($durationDays); // +durationDays (chặn thêm 1 ngày sau khi tour kết thúc)
            }

            // Lấy tất cả departures đã được gán HDV (kể cả cùng tour, để tránh trùng lịch giữa các ngày)
            $assignedDepartures = TourDeparture::whereNotNull('guide_id')
                ->with('tour')
                ->get();

            foreach ($assignedDepartures as $departure) {
                $depStart = $departure->departure_date instanceof \Carbon\Carbon
                    ? $departure->departure_date->copy()->startOfDay()
                    : \Carbon\Carbon::parse($departure->departure_date)->startOfDay();

                $depTour = $departure->tour;
                $depDuration = $this->getTourDurationDays($depTour);
                $depEnd = (clone $depStart)->addDays($depDuration); // +depDuration

                // Nếu khoảng thời gian tour mới giao với khoảng thời gian tour đã gán => HDV bận
                if ($startDate <= $depEnd && $endDate >= $depStart) {
                    $busyGuideIds[] = $departure->guide_id;
                }
            }

            $busyGuideIds = array_values(array_unique($busyGuideIds));
        }

        // Lọc ra các HDV có sẵn (chưa bị bận trong khoảng thời gian đó)
        $availableGuides = $allGuides->reject(function($guide) use ($busyGuideIds) {
            return in_array($guide->id, $busyGuideIds);
        })->values();

        return response()->json([
            'success' => true,
            'data' => $availableGuides->map(function($guide) {
                return [
                    'id' => $guide->id,
                    'name' => $guide->name,
                    'email' => $guide->email,
                ];
            })
        ]);
    }

    /**
     * API: Lấy danh sách xe có sẵn cho một khoảng thời gian tour (theo ngày khởi hành + số ngày tour)
     */
    public function getAvailableVehicles(Request $request)
    {
        $request->validate([
            'departure_date' => 'nullable|date',
            'tour_id' => 'nullable|exists:tours,id',
        ]);

        // Lấy tất cả xe đang hoạt động
        $allVehicles = \App\Models\Vehicle::where('status', 1)
            ->orderBy('license_plate')
            ->get();

        $busyVehicleIds = [];

        // Chỉ lọc xe bận nếu có ngày khởi hành hợp lệ
        if ($request->filled('departure_date')) {
            $startDate = \Carbon\Carbon::parse($request->departure_date)->startOfDay();

            // Tính ngày kết thúc của tour hiện tại (nếu có tour_id thì dùng duration_days)
            // Quy ước: chặn thêm 1 ngày sau khi tour kết thúc để đảm bảo HDV/xe về kịp
            $endDate = (clone $startDate);
            if ($request->filled('tour_id')) {
                $currentTour = Tour::find($request->tour_id);
                $durationDays = $this->getTourDurationDays($currentTour);
                $endDate->addDays($durationDays); // +durationDays
            }

            // Lấy tất cả departures đã được gán xe (kể cả cùng tour)
            $assignedDepartures = TourDeparture::whereNotNull('vehicle_id')
                ->with('tour')
                ->get();

            foreach ($assignedDepartures as $departure) {
                $depStart = $departure->departure_date instanceof \Carbon\Carbon
                    ? $departure->departure_date->copy()->startOfDay()
                    : \Carbon\Carbon::parse($departure->departure_date)->startOfDay();

                $depTour = $departure->tour;
                $depDuration = $this->getTourDurationDays($depTour);
                $depEnd = (clone $depStart)->addDays($depDuration); // +depDuration

                // Nếu khoảng thời gian tour mới giao với khoảng thời gian tour đã gán => xe bận
                if ($startDate <= $depEnd && $endDate >= $depStart) {
                    $busyVehicleIds[] = $departure->vehicle_id;
                }
            }

            $busyVehicleIds = array_values(array_unique($busyVehicleIds));
        }

        // Lọc ra các xe có sẵn (chưa bị bận trong khoảng thời gian đó)
        $availableVehicles = $allVehicles->reject(function($vehicle) use ($busyVehicleIds) {
            return in_array($vehicle->id, $busyVehicleIds);
        })->values();

        return response()->json([
            'success' => true,
            'data' => $availableVehicles->map(function($vehicle) {
                $typeMap = ['16' => '16 chỗ', '29' => '29 chỗ', '45' => '45 chỗ'];
                $typeLabel = $typeMap[$vehicle->vehicle_type] ?? ($vehicle->vehicle_type . ' chỗ');
                $label = '[' . $typeLabel . '] ' . ($vehicle->brand ?? '') . ' ' . ($vehicle->color ?? '') . ' - ' . $vehicle->license_plate;
                
                return [
                    'id' => $vehicle->id,
                    'license_plate' => $vehicle->license_plate,
                    'vehicle_type' => $vehicle->vehicle_type,
                    'brand' => $vehicle->brand,
                    'color' => $vehicle->color,
                    'label' => trim($label),
                ];
            })
        ]);
    }

    /**
     * B3: Gán hướng dẫn viên (HDV)
     *
     * Yêu cầu: HDV không được bận ở bất kỳ tour nào có khoảng thời gian
     * giao với khoảng ngày của tour hiện tại (ngày khởi hành + số ngày tour).
     */
    public function assignGuide(Request $request)
    {
        $request->validate([
            'departure_date' => 'required|date',
            'tour_id' => 'required|exists:tours,id',
            'guide_id' => 'required|exists:users,id',
        ]);

        $tour = Tour::findOrFail($request->tour_id);
        $guide = User::findOrFail($request->guide_id);

        // Tính khoảng thời gian của tour hiện tại
        $startDate = \Carbon\Carbon::parse($request->departure_date)->startOfDay();
        $durationDays = $this->getTourDurationDays($tour);
        // +durationDays để chặn luôn các tour khởi hành đúng ngày xe/HDV vừa kết thúc tour này
        $endDate = (clone $startDate)->addDays($durationDays);

        // Lấy tất cả departures mà HDV này đã được gán (kể cả cùng tour),
        // để tránh trùng lịch giữa các lần khởi hành khác nhau của cùng một tour.
        $assignedDepartures = TourDeparture::where('guide_id', $guide->id)
            ->with('tour')
            ->get();

        foreach ($assignedDepartures as $departure) {
            $depStart = $departure->departure_date instanceof \Carbon\Carbon
                ? $departure->departure_date->copy()->startOfDay()
                : \Carbon\Carbon::parse($departure->departure_date)->startOfDay();

            $depTour = $departure->tour;
            $depDuration = $this->getTourDurationDays($depTour);
            $depEnd = (clone $depStart)->addDays($depDuration);

            // Nếu khoảng ngày giao nhau => không cho gán
            if ($startDate <= $depEnd && $endDate >= $depStart) {
            return response()->json([
                'success' => false,
                    'message' => 'Hướng dẫn viên này đã được gán cho tour khác trong khoảng thời gian từ '
                        . $depStart->format('d/m/Y') . ' đến ' . $depEnd->format('d/m/Y')
                        . '. Vui lòng chọn HDV khác để tránh trùng lịch.',
            ], 422);
            }
        }

        // Lấy tất cả departures của tour này trong ngày khởi hành
        $departures = TourDeparture::whereDate('departure_date', $request->departure_date)
            ->where('tour_id', $request->tour_id)
            ->get();
        
        if ($departures->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy lịch khởi hành cho tour này trong ngày này.',
            ], 404);
        }

        // Gán guide cho tất cả departures của tour này trong ngày đó
        foreach ($departures as $departure) {
            $departure->update([
                'guide_id' => $guide->id,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã gán hướng dẫn viên thành công!',
            'data' => [
                'departure_date' => $request->departure_date,
                'tour_id' => $request->tour_id,
                'guide_id' => $guide->id,
                'guide_name' => $guide->name,
            ],
        ]);
    }

    /**
     * B4: Gán xe từ danh sách Quản lý xe
     *
     * Yêu cầu: Xe không được bận ở bất kỳ tour nào có khoảng thời gian
     * giao với khoảng ngày của tour hiện tại (ngày khởi hành + số ngày tour).
     */
    public function assignVehicle(Request $request)
    {
        $request->validate([
            'departure_date' => 'required|date',
            'tour_id' => 'required|exists:tours,id',
            'vehicle_id' => 'required|exists:vehicles,id',
        ]);

        $tour = Tour::findOrFail($request->tour_id);
        $vehicle = \App\Models\Vehicle::findOrFail($request->vehicle_id);

        // Tính khoảng thời gian của tour hiện tại
        $startDate = \Carbon\Carbon::parse($request->departure_date)->startOfDay();
        $durationDays = $this->getTourDurationDays($tour);
        // +durationDays để chặn luôn các tour khởi hành đúng ngày xe này vừa kết thúc tour khác
        $endDate = (clone $startDate)->addDays($durationDays);

        // Lấy tất cả departures mà xe này đã được gán (kể cả cùng tour)
        $assignedDepartures = TourDeparture::where('vehicle_id', $vehicle->id)
            ->with('tour')
            ->get();

        foreach ($assignedDepartures as $departure) {
            $depStart = $departure->departure_date instanceof \Carbon\Carbon
                ? $departure->departure_date->copy()->startOfDay()
                : \Carbon\Carbon::parse($departure->departure_date)->startOfDay();

            $depTour = $departure->tour;
            $depDuration = $this->getTourDurationDays($depTour);
            $depEnd = (clone $depStart)->addDays($depDuration);

            // Nếu khoảng ngày giao nhau => không cho gán
            if ($startDate <= $depEnd && $endDate >= $depStart) {
            return response()->json([
                'success' => false,
                    'message' => 'Xe ' . $vehicle->license_plate . ' đã được gán cho tour khác trong khoảng thời gian từ '
                        . $depStart->format('d/m/Y') . ' đến ' . $depEnd->format('d/m/Y')
                        . '. Vui lòng chọn xe khác để tránh trùng lịch.',
            ], 422);
            }
        }

        // Lấy tất cả departures của tour này trong ngày khởi hành
        $departures = TourDeparture::whereDate('departure_date', $request->departure_date)
            ->where('tour_id', $request->tour_id)
            ->get();
        
        if ($departures->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy lịch khởi hành cho tour này trong ngày này.',
            ], 404);
        }

        // Chuẩn bị thông tin xe hiển thị
        $vehicleDetails = trim(($vehicle->brand ? $vehicle->brand . ' ' : '') .
            ($vehicle->color ? $vehicle->color . ' ' : '') .
            '(' . $vehicle->license_plate . ')');

        $driverContact = trim(($vehicle->driver_name ? $vehicle->driver_name . ' - ' : '') .
            ($vehicle->driver_phone ?? ''));

        // Gán xe cho tất cả departures của tour này trong ngày đó
        foreach ($departures as $departure) {
            $departure->update([
                'vehicle_id' => $vehicle->id,
                'vehicle_type' => $vehicle->vehicle_type,
                'vehicle_details' => $vehicleDetails,
                'driver_contact' => $driverContact ?: null,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã gán xe ' . $vehicle->license_plate . ' thành công!',
            'data' => [
                'departure_date' => $request->departure_date,
                'tour_id' => $request->tour_id,
                'vehicle_id' => $vehicle->id,
                'vehicle_type' => $vehicle->vehicle_type,
                'vehicle_details' => $vehicleDetails,
                'driver_contact' => $driverContact,
            ],
        ]);
    }

    /**
     * B5: Gửi thông tin trước tour cho khách
     */
    public function sendPreTourInfo(Request $request)
    {
        $request->validate([
            'departure_date' => 'required|date',
            'message' => 'nullable|string',
            'send_email' => 'boolean',
        ]);

        // Lấy tất cả bookings có cùng ngày khởi hành
        $bookings = Booking::whereHas('departure', function($query) use ($request) {
            $query->whereDate('departure_date', $request->departure_date);
        })->with(['user', 'tour', 'departure'])->get();

        if ($bookings->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đặt tour nào cho ngày này.'
            ], 404);
        }

        $notificationService = new \App\Services\NotificationService();
        $sentCount = 0;
        $errors = [];

        foreach ($bookings as $booking) {
            try {
                $title = 'Thông tin trước tour - ' . $booking->tour->title;
                $message = $request->message ?? "Tour của bạn sẽ khởi hành vào ngày " . 
                    $booking->departure->departure_date->format('d/m/Y') . 
                    ". Vui lòng chuẩn bị sẵn sàng!";

                $notificationService->sendNotification(
                    $booking->user,
                    \App\Services\NotificationService::TYPE_DEPARTURE_UPCOMING,
                    $title,
                    $message,
                    $booking->id,
                    'booking',
                    $request->send_email ?? true
                );
                $sentCount++;
            } catch (\Exception $e) {
                $errors[] = "Lỗi gửi thông tin cho khách hàng {$booking->user->name}: " . $e->getMessage();
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Đã gửi thông tin cho {$sentCount} khách hàng.",
            'data' => [
                'sent_count' => $sentCount,
                'total_count' => $bookings->count(),
                'errors' => $errors,
            ]
        ]);
    }
}
