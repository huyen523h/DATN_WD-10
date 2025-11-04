<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function tours()
    {
        $tours = Tour::with(['category', 'images'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('staff.tours.index', compact('tours'));
    }

    public function showTour(Tour $tour)
    {
        $tour->load(['category', 'images', 'bookings.user']);
        
        return view('staff.tours.show', compact('tour'));
    }

    public function bookings()
    {
        $bookings = Booking::with(['user', 'tour'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('staff.bookings.index', compact('bookings'));
    }

    public function showBooking(Booking $booking)
    {
        $booking->load(['user', 'tour', 'tour.images']);
        
        return view('staff.bookings.show', compact('booking'));
    }

    public function updateBooking(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'notes' => 'nullable|string|max:500'
        ]);

        $booking->update($request->only(['status', 'notes']));

        return redirect()->route('staff.bookings.show', $booking)
            ->with('success', 'Cập nhật booking thành công.');
    }

    public function customers()
    {
        $customers = User::whereHas('roles', function ($q) {
            $q->where('name', 'customer');
        })->with(['bookings' => function ($query) {
            $query->latest()->limit(3);
        }])
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        return view('staff.customers.index', compact('customers'));
    }

    public function showCustomer(User $user)
    {
        $user->load(['bookings.tour', 'reviews.tour']);
        
        return view('staff.customers.show', compact('user'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('staff.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $user->update($request->only(['name', 'email', 'phone', 'address']));

        return redirect()->route('staff.profile')
            ->with('success', 'Cập nhật thông tin thành công.');
    }
}