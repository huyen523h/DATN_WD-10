<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB; 
use App\Models\Tour;
use App\Models\Booking;
use App\Models\User;
use App\Models\Category;
use App\Models\TourImage;
use App\Models\TourSchedule;
use App\Models\TourDeparture;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_tours' => Tour::count(),
            'total_bookings' => Booking::count(),
            'total_customers' => User::whereHas('roles', function($query) {
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
       $query = Tour::with(['category', 'images', 'bookings']);

    // Tìm kiếm theo tiêu đề/mô tả/danh mục
    if ($request->filled('search')) {
        $search = $request->string('search')->toString();
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhereHas('category', fn ($cq) =>
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
                   ->appends($request->only(['search','category_id','availability_status']));
    $categories = Category::orderBy('name')->get();

    // Trả kèm giá trị filter hiện tại để giữ selected trong view
    return view('admin.tours', [
        'tours'               => $tours,
        'categories'          => $categories,
        'availabilityCurrent' => $av,
    ]);
    }

    public function createTour(): View
    {
        $categories = Category::all();
        return view('admin.tours.create', compact('categories'));
    }

    public function storeTour(Request $request): RedirectResponse
    {
         $validated = $request->validate([
        'title'          => 'required|string|max:255',
        'description'    => 'required|string',
        'category_id'    => 'required|exists:categories,id',
        'duration'       => 'nullable|string|max:50',
        'duration_days'  => 'nullable|integer|min:1|max:60',
        'nights'         => 'nullable|integer|min:0|max:59',
        'price'          => 'required|numeric|min:0',
        'original_price' => 'nullable|numeric|min:0',
        'discount_price' => 'nullable|numeric|min:0',
        'price_adult'    => 'nullable|numeric|min:0',
        'price_child'    => 'nullable|numeric|min:0',
        'price_infant'   => 'nullable|numeric|min:0',
        'includes'            => 'nullable|string',
        'excludes'            => 'nullable|string',
        'surcharges'          => 'nullable|string',
        'notes'               => 'nullable|string',
        'cancellation_policy' => 'nullable|string',
        'visa_requirements'   => 'nullable|string',
        // CHỈ CÒN availability_status
        'availability_status' => 'nullable|in:available,contact,sold_out',

        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'schedule_day_number.*'  => 'nullable|integer|min:1|max:60',
        'schedule_title.*'       => 'nullable|string|max:255',
        'schedule_description.*' => 'nullable|string',
        'departure_date.*'  => 'nullable|date|after:today',
        'seats_total.*'     => 'nullable|integer|min:1|max:100',
        'seats_available.*' => 'nullable|integer|min:0|max:100',
        'price_dep.*'       => 'nullable|numeric|min:0',
        'child_price.*'     => 'nullable|numeric|min:0',
        'infant_price.*'    => 'nullable|numeric|min:0',
        'status_dep.*'      => 'nullable|in:available,contact,sold_out',
    ]);

    $tour = \App\Models\Tour::create([
        'title'               => $validated['title'],
        'description'         => $validated['description'],
        'category_id'         => $validated['category_id'],
        'duration'            => $validated['duration'] ?? null,
        'duration_days'       => $validated['duration_days'] ?? null,
        'nights'              => $validated['nights'] ?? null,
        'price'               => $validated['price'],
        'original_price'      => $validated['original_price'] ?? null,
        'discount_price'      => $validated['discount_price'] ?? null,
        'price_adult'         => $validated['price_adult'] ?? null,
        'price_child'         => $validated['price_child'] ?? null,
        'price_infant'        => $validated['price_infant'] ?? null,
        'includes'            => $validated['includes'] ?? null,
        'excludes'            => $validated['excludes'] ?? null,
        'surcharges'          => $validated['surcharges'] ?? null,
        'notes'               => $validated['notes'] ?? null,
        'cancellation_policy' => $validated['cancellation_policy'] ?? null,
        'visa_requirements'   => $validated['visa_requirements'] ?? null,
        'availability_status' => $validated['availability_status'] ?? 'available',
    ]);

    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $i => $image) {
            $path = $image->store('tours', 'public');
            \App\Models\TourImage::create([
                'tour_id'    => $tour->id,
                'image_url'  => \Illuminate\Support\Facades\Storage::url($path),
                'is_cover'   => $i === 0,
                'sort_order' => $i + 1,
            ]);
        }
    }

    if ($request->has('schedule_day_number')) {
        foreach ($request->schedule_day_number as $i => $dayNum) {
            if (!empty($request->schedule_title[$i])) {
                \App\Models\TourSchedule::create([
                    'tour_id'     => $tour->id,
                    'day_number'  => $dayNum,
                    'title'       => $request->schedule_title[$i],
                    'description' => $request->schedule_description[$i] ?? '',
                ]);
            }
        }
    }

    if ($request->has('departure_date')) {
        foreach ($request->departure_date as $i => $date) {
            if (!empty($date)) {
                \App\Models\TourDeparture::create([
                    'tour_id'         => $tour->id,
                    'departure_date'  => $date,
                    'seats_total'     => $request->seats_total[$i] ?? 20,
                    'seats_available' => $request->seats_available[$i] ?? 20,
                    'price'           => $request->price_dep[$i] ?? ($validated['price_adult'] ?? $validated['price']),
                    'child_price'     => $request->child_price[$i]  ?? ($validated['price_child']  ?? null),
                    'infant_price'    => $request->infant_price[$i] ?? ($validated['price_infant'] ?? null),
                    'status'          => $request->status_dep[$i] ?? 'available',
                ]);
            }
        }
    }

    return redirect()->route('admin.tours')->with('success', 'Tour đã được tạo thành công!');
    }

    public function editTour(Tour $tour): View
    {
        $tour->load(['category', 'images', 'schedules', 'departures']);
        $categories = Category::all();
        return view('admin.tours.edit', compact('tour', 'categories'));
    }

    public function updateTour(Request $request, Tour $tour): RedirectResponse
    {
         $validated = $request->validate([
        'title'          => 'required|string|max:255',
        'description'    => 'required|string',
        'category_id'    => 'required|exists:categories,id',
        'duration'       => 'nullable|string|max:50',
        'duration_days'  => 'nullable|integer|min:1|max:60',
        'nights'         => 'nullable|integer|min:0|max:59',
        'price'          => 'required|numeric|min:0',
        'original_price' => 'nullable|numeric|min:0',
        'discount_price' => 'nullable|numeric|min:0',
        'price_adult'    => 'nullable|numeric|min:0',
        'price_child'    => 'nullable|numeric|min:0',
        'price_infant'   => 'nullable|numeric|min:0',
        'includes'            => 'nullable|string',
        'excludes'            => 'nullable|string',
        'surcharges'          => 'nullable|string',
        'notes'               => 'nullable|string',
        'cancellation_policy' => 'nullable|string',
        'visa_requirements'   => 'nullable|string',
        'availability_status' => 'nullable|in:available,contact,sold_out',

        // CHỈ cần upload khi muốn thay ảnh; nếu không upload thì giữ ảnh cũ
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',

        // schedules
        'schedule_day_number.*'  => 'nullable|integer|min:1|max:60',
        'schedule_title.*'       => 'nullable|string|max:255',
        'schedule_description.*' => 'nullable|string',
        // departures
        'departure_date.*'  => 'nullable|date|after:today',
        'seats_total.*'     => 'nullable|integer|min:1|max:100',
        'seats_available.*' => 'nullable|integer|min:0|max:100',
        'price_dep.*'       => 'nullable|numeric|min:0',
        'child_price.*'     => 'nullable|numeric|min:0',
        'infant_price.*'    => 'nullable|numeric|min:0',
        'status_dep.*'      => 'nullable|in:available,contact,sold_out',
    ]);

    DB::transaction(function () use ($request, $tour, $validated) {

        // 1) Update các trường cơ bản
        $tour->update([
            'title'               => $validated['title'],
            'description'         => $validated['description'],
            'category_id'         => $validated['category_id'],
            'duration'            => $validated['duration'] ?? null,
            'duration_days'       => $validated['duration_days'] ?? null,
            'nights'              => $validated['nights'] ?? null,
            'price'               => $validated['price'],
            'original_price'      => $validated['original_price'] ?? null,
            'discount_price'      => $validated['discount_price'] ?? null,
            'price_adult'         => $validated['price_adult'] ?? null,
            'price_child'         => $validated['price_child'] ?? null,
            'price_infant'        => $validated['price_infant'] ?? null,
            'includes'            => $validated['includes'] ?? null,
            'excludes'            => $validated['excludes'] ?? null,
            'surcharges'          => $validated['surcharges'] ?? null,
            'notes'               => $validated['notes'] ?? null,
            'cancellation_policy' => $validated['cancellation_policy'] ?? null,
            'visa_requirements'   => $validated['visa_requirements'] ?? null,
            'availability_status' => $validated['availability_status'] ?? 'available',
        ]);

        // 2) Nếu có upload ảnh mới => XOÁ toàn bộ ảnh cũ rồi lưu ảnh mới
        if ($request->hasFile('images')) {
            // xoá file + record cũ
            foreach ($tour->images as $old) {
                $path = str_replace('/storage/', '', $old->image_url);   // '/storage/...' -> '...'
                Storage::disk('public')->delete($path);
                $old->delete();
            }

            // thêm ảnh mới (ảnh đầu tiên là cover)
            $order = 1;
            foreach ($request->file('images') as $idx => $image) {
                if (!$image) continue;
                $path = $image->store('tours', 'public');
                TourImage::create([
                    'tour_id'    => $tour->id,
                    'image_url'  => Storage::url($path), // /storage/...
                    'is_cover'   => $idx === 0,          // ảnh đầu tiên làm cover
                    'sort_order' => $order++,
                ]);
            }
        }

        // 3) schedules: clear & tạo lại
        $tour->schedules()->delete();
        if ($request->has('schedule_day_number')) {
            foreach ($request->schedule_day_number as $i => $dayNum) {
                if (!empty($request->schedule_title[$i])) {
                    \App\Models\TourSchedule::create([
                        'tour_id'     => $tour->id,
                        'day_number'  => $dayNum,
                        'title'       => $request->schedule_title[$i],
                        'description' => $request->schedule_description[$i] ?? '',
                    ]);
                }
            }
        }

        // 4) departures: clear & tạo lại với fallback giá
        $tour->departures()->delete();
        if ($request->has('departure_date')) {
            foreach ($request->departure_date as $i => $date) {
                if (!empty($date)) {
                    \App\Models\TourDeparture::create([
                        'tour_id'         => $tour->id,
                        'departure_date'  => $date,
                        'seats_total'     => $request->seats_total[$i] ?? 20,
                        'seats_available' => $request->seats_available[$i] ?? 20,
                        'price'           => $request->price_dep[$i] ?? ($validated['price_adult'] ?? $validated['price']),
                        'child_price'     => $request->child_price[$i]  ?? ($validated['price_child']  ?? null),
                        'infant_price'    => $request->infant_price[$i] ?? ($validated['price_infant'] ?? null),
                        'status'          => $request->status_dep[$i] ?? 'available',
                    ]);
                }
            }
        }
    });

    return redirect()
        ->route('admin.tours')
        ->with('success', 'Tour đã được cập nhật thành công!');
    }

    public function deleteTour(Tour $tour): RedirectResponse
    {
        $tour->delete();
        return redirect()->route('admin.tours')->with('success', 'Tour đã được xóa thành công!');
    }

    public function bookings()
    {
        $bookings = Booking::with(['tour', 'user', 'departure'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.bookings', compact('bookings'));
    }

    public function customers()
    {
        $customers = User::whereHas('roles', function($query) {
            $query->where('name', 'customer');
        })->with(['bookings'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        
        return view('admin.customers', compact('customers'));
    }

    public function categories()
    {
        $categories = Category::withCount('tours')->paginate(10);
        return view('admin.categories', compact('categories'));
    }

    public function reviews()
    {
        $reviews = \App\Models\Review::with(['tour', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.reviews', compact('reviews'));
    }

    public function payments()
    {
        $payments = \App\Models\Payment::with(['booking.user', 'booking.tour'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.payments', compact('payments'));
    }

    public function reports()
    {
        $stats = [
            'total_revenue' => \App\Models\Payment::where('status', 'completed')->sum('amount'),
            'monthly_revenue' => \App\Models\Payment::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
            'total_bookings' => Booking::count(),
            'completed_bookings' => Booking::where('status', 'completed')->count(),
        ];
        
        return view('admin.reports', compact('stats'));
    }

    public function notifications()
    {
        $notifications = \App\Models\Notification::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.notifications', compact('notifications'));
    }

    public function support()
    {
        $tickets = \App\Models\SupportTicket::with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.support', compact('tickets'));
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
    public function updateBooking(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'notes' => 'nullable|string',
        ]);

        $booking->update($validated);

        return redirect()->route('admin.bookings')->with('success', 'Đặt tour đã được cập nhật thành công!');
    }

    public function deleteBooking(Booking $booking): RedirectResponse
    {
        $booking->delete();
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
}


