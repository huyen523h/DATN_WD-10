<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB; 
use App\Models\Tour;
use App\Models\Booking;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_tours' => Tour::count(),
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'confirmed_bookings' => Booking::where('status', 'confirmed')->count(),
        ];

        $recent_bookings = Booking::with(['tour', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('staff.dashboard', compact('stats', 'recent_bookings'));
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

        $tours = $query->orderByDesc('created_at')->paginate(10)
                       ->appends($request->only(['search','category_id']));
        $categories = Category::orderBy('name')->get();

        return view('staff.tours', [
            'tours' => $tours,
            'categories' => $categories,
        ]);
    }

    public function showTour(Tour $tour): View
    {
        $tour->load(['category', 'images', 'schedules', 'departures', 'bookings.user', 'reviews.user']);
        return view('staff.tours.show', compact('tour'));
    }

    public function bookings()
    {
        $bookings = Booking::with(['tour', 'user', 'departure'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('staff.bookings.index', compact('bookings'));
    }

    public function showBooking(Booking $booking): View
    {
        $booking->load(['tour', 'user', 'departure', 'payments', 'documents', 'chat.messages.sender']);
        return view('staff.bookings.show', compact('booking'));
    }

    public function updateBooking(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'notes' => 'nullable|string',
        ]);

        $booking->update($validated);

        return redirect()->route('staff.bookings')->with('success', 'Đặt tour đã được cập nhật thành công!');
    }

    public function customers()
    {
        $customers = User::whereHas('roles', function($query) {
            $query->where('name', 'customer');
        })->with(['bookings'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);
        
        return view('staff.customers.index', compact('customers'));
    }

    public function showCustomer(User $user): View
    {
        $user->load(['bookings.tour', 'reviews.tour', 'supportTickets', 'notifications']);
        return view('staff.customers.show', compact('user'));
    }

    public function profile()
    {
        return view('staff.profile');
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        auth()->user()->update($validated);

        return redirect()->route('staff.profile')->with('success', 'Thông tin cá nhân đã được cập nhật thành công!');
    }
}
