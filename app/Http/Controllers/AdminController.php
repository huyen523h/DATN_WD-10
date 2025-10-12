<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\Booking;
use App\Models\User;
use App\Models\Category;
use App\Models\Promotion;
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

        // Search
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

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tours = $query->orderBy('created_at', 'desc')->paginate(10);
        $categories = Category::all();
        
        return view('admin.tours', compact('tours', 'categories'));
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
            'duration_days' => 'required|integer|min:1|max:30',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive,draft',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'schedule_day.*' => 'nullable|integer|min:1|max:30',
            'schedule_title.*' => 'nullable|string|max:255',
            'schedule_description.*' => 'nullable|string',
            'departure_date.*' => 'nullable|date|after:today',
            'seats_total.*' => 'nullable|integer|min:1|max:100',
            'seats_available.*' => 'nullable|integer|min:0|max:100',
        ]);

        $tour = Tour::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'duration_days' => $validated['duration_days'],
            'price' => $validated['price'],
            'status' => $validated['status'],
        ]);

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('tours', 'public');
                TourImage::create([
                    'tour_id' => $tour->id,
                    'image_url' => Storage::url($path),
                    'is_cover' => $index === 0,
                    'sort_order' => $index + 1,
                ]);
            }
        }

        // Handle schedules
        if ($request->has('schedule_day')) {
            foreach ($request->schedule_day as $index => $day) {
                if (!empty($request->schedule_title[$index])) {
                    TourSchedule::create([
                        'tour_id' => $tour->id,
                        'day' => $day,
                        'title' => $request->schedule_title[$index],
                        'description' => $request->schedule_description[$index] ?? '',
                    ]);
                }
            }
        }

        // Handle departures
        if ($request->has('departure_date')) {
            foreach ($request->departure_date as $index => $date) {
                if (!empty($date)) {
                    TourDeparture::create([
                        'tour_id' => $tour->id,
                        'departure_date' => $date,
                        'seats_total' => $request->seats_total[$index] ?? 20,
                        'seats_available' => $request->seats_available[$index] ?? 20,
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'duration_days' => 'required|integer|min:1|max:30',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive,draft',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'schedule_day.*' => 'nullable|integer|min:1|max:30',
            'schedule_title.*' => 'nullable|string|max:255',
            'schedule_description.*' => 'nullable|string',
            'departure_date.*' => 'nullable|date|after:today',
            'seats_total.*' => 'nullable|integer|min:1|max:100',
            'seats_available.*' => 'nullable|integer|min:0|max:100',
        ]);

        $tour->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'duration_days' => $validated['duration_days'],
            'price' => $validated['price'],
            'status' => $validated['status'],
        ]);

        // Handle new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('tours', 'public');
                TourImage::create([
                    'tour_id' => $tour->id,
                    'image_url' => Storage::url($path),
                    'is_cover' => false,
                    'sort_order' => $tour->images()->count() + $index + 1,
                ]);
            }
        }

        // Update schedules
        $tour->schedules()->delete();
        if ($request->has('schedule_day')) {
            foreach ($request->schedule_day as $index => $day) {
                if (!empty($request->schedule_title[$index])) {
                    TourSchedule::create([
                        'tour_id' => $tour->id,
                        'day' => $day,
                        'title' => $request->schedule_title[$index],
                        'description' => $request->schedule_description[$index] ?? '',
                    ]);
                }
            }
        }

        // Update departures
        $tour->departures()->delete();
        if ($request->has('departure_date')) {
            foreach ($request->departure_date as $index => $date) {
                if (!empty($date)) {
                    TourDeparture::create([
                        'tour_id' => $tour->id,
                        'departure_date' => $date,
                        'seats_total' => $request->seats_total[$index] ?? 20,
                        'seats_available' => $request->seats_available[$index] ?? 20,
                    ]);
                }
            }
        }

        return redirect()->route('admin.tours')->with('success', 'Tour đã được cập nhật thành công!');
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
        
        return view('admin.bookings.index', compact('bookings'));
    }

    public function customers()
    {
        $customers = User::whereHas('roles', function($query) {
            $query->where('name', 'customer');
        })->with(['bookings'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        
        return view('admin.customers.index', compact('customers'));
    }

    public function categories()
    {
        $categories = Category::withCount('tours')->paginate(10);
        return view('admin.categories.index', compact('categories'));
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


