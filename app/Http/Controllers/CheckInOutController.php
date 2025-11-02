<?php

namespace App\Http\Controllers;

use App\Models\CheckInOut;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CheckInOutController extends Controller
{
    /**
     * Display a listing of check-in/out records
     */
    public function index(Request $request)
    {
        $query = CheckInOut::with(['user', 'booking.tour']);

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('check_time', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('check_time', '<=', $request->date_to);
        }

        // Search by user name or booking code
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('booking', function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%");
            });
        }

        $checkInOuts = $query->orderBy('check_time', 'desc')->paginate(20);

        // Statistics
        $stats = [
            'total_today' => CheckInOut::today()->count(),
            'check_ins_today' => CheckInOut::today()->checkIn()->count(),
            'check_outs_today' => CheckInOut::today()->checkOut()->count(),
            'pending_count' => CheckInOut::pending()->count(),
            'confirmed_count' => CheckInOut::confirmed()->count(),
        ];

        return view('admin.check-in-out.index', compact('checkInOuts', 'stats'));
    }

    /**
     * Show the form for creating a new check-in/out
     */
    public function create()
    {
        $bookings = Booking::with(['user', 'tour'])
            ->where('status', 'confirmed')
            ->where('departure_date', '>=', today())
            ->get();

        return view('admin.check-in-out.create', compact('bookings'));
    }

    /**
     * Store a newly created check-in/out
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'booking_id' => 'required|exists:bookings,id',
            'type' => 'required|in:check_in,check_out',
            'check_time' => 'required|date',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'notes' => 'nullable|string|max:1000',
            'metadata' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        // Check if user already checked in/out for this booking
        $existingCheck = CheckInOut::where('user_id', $request->user_id)
            ->where('booking_id', $request->booking_id)
            ->where('type', $request->type)
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($existingCheck) {
            $errorMessage = 'Người dùng đã thực hiện ' . ($request->type === 'check_in' ? 'check-in' : 'check-out') . ' cho booking này';
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 400);
            }
            return back()->with('error', $errorMessage)->withInput();
        }

        $checkInOut = CheckInOut::create([
            'user_id' => $request->user_id,
            'booking_id' => $request->booking_id,
            'type' => $request->type,
            'check_time' => $request->check_time,
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'notes' => $request->notes,
            'metadata' => $request->metadata,
            'status' => 'pending'
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tạo ' . ($request->type === 'check_in' ? 'check-in' : 'check-out') . ' thành công',
                'data' => $checkInOut->load(['user', 'booking'])
            ]);
        }

        return redirect()->route('admin.check-in-out.show', $checkInOut)
            ->with('success', 'Tạo ' . ($request->type === 'check_in' ? 'check-in' : 'check-out') . ' thành công!');
    }

    /**
     * Display the specified check-in/out
     */
    public function show(CheckInOut $checkInOut)
    {
        $checkInOut->load(['user', 'booking.tour']);
        
        return view('admin.check-in-out.show', compact('checkInOut'));
    }

    /**
     * Show the form for editing the specified check-in/out
     */
    public function edit(CheckInOut $checkInOut)
    {
        $bookings = Booking::with(['user', 'tour'])
            ->where('status', 'confirmed')
            ->get();

        return view('admin.check-in-out.edit', compact('checkInOut', 'bookings'));
    }

    /**
     * Update the specified check-in/out
     */
    public function update(Request $request, CheckInOut $checkInOut)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:check_in,check_out',
            'check_time' => 'required|date',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,confirmed,cancelled',
            'metadata' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $checkInOut->update($request->all());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thành công',
                'data' => $checkInOut->load(['user', 'booking'])
            ]);
        }

        return redirect()->route('admin.check-in-out.show', $checkInOut)
            ->with('success', 'Cập nhật check-in/check-out thành công!');
    }

    /**
     * Remove the specified check-in/out
     */
    public function destroy(Request $request, CheckInOut $checkInOut)
    {
        $checkInOut->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Xóa thành công'
            ]);
        }

        return redirect()->route('admin.check-in-out.index')
            ->with('success', 'Xóa check-in/check-out thành công!');
    }

    /**
     * Confirm a check-in/out
     */
    public function confirm(Request $request, CheckInOut $checkInOut)
    {
        $verifiedBy = Auth::check() ? Auth::user()->name : 'System';
        $checkInOut->confirm($verifiedBy);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Xác nhận thành công'
            ]);
        }

        return redirect()->back()->with('success', 'Xác nhận check-in/check-out thành công!');
    }

    /**
     * Cancel a check-in/out
     */
    public function cancel(Request $request, CheckInOut $checkInOut)
    {
        $checkInOut->cancel();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Hủy thành công'
            ]);
        }

        return redirect()->back()->with('success', 'Hủy check-in/check-out thành công!');
    }

    /**
     * Get statistics for dashboard
     */
    public function statistics(Request $request)
    {
        $period = $request->get('period', 'today');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        $query = CheckInOut::query();
        
        // Apply date filters
        if ($dateFrom && $dateTo) {
            $query->whereBetween('check_time', [$dateFrom, $dateTo]);
        } else {
            switch ($period) {
                case 'today':
                    $query->today();
                    break;
                case 'week':
                    $query->thisWeek();
                    break;
                case 'month':
                    $query->thisMonth();
                    break;
                case 'year':
                    $query->whereYear('check_time', now()->year);
                    break;
            }
        }

        $stats = [
            'total' => $query->count(),
            'check_ins' => $query->clone()->checkIn()->count(),
            'check_outs' => $query->clone()->checkOut()->count(),
            'pending' => $query->clone()->pending()->count(),
            'confirmed' => $query->clone()->confirmed()->count(),
            'cancelled' => $query->clone()->where('status', 'cancelled')->count(),
        ];

        // Daily stats for chart
        $dailyQuery = CheckInOut::query();
        
        if ($dateFrom && $dateTo) {
            $dailyQuery->whereBetween('check_time', [$dateFrom, $dateTo]);
        } else {
            // Using switch instead of match() for PHP 8.0 compatibility
            switch($period) {
                case 'today':
                    $startDate = now()->startOfDay();
                    break;
                case 'week':
                    $startDate = now()->startOfWeek();
                    break;
                case 'month':
                    $startDate = now()->startOfMonth();
                    break;
                case 'year':
                    $startDate = now()->startOfYear();
                    break;
                default:
                    $startDate = now()->subDays(30);
                    break;
            }
            $endDate = now();
            $dailyQuery->whereBetween('check_time', [$startDate, $endDate]);
        }

        $dailyStats = $dailyQuery->selectRaw('DATE(check_time) as date, type, status, COUNT(*) as count')
            ->groupBy('date', 'type', 'status')
            ->orderBy('date')
            ->get()
            ->groupBy('date')
            ->map(function ($dayData) {
                $result = [];
                foreach ($dayData as $item) {
                    $result[$item->type] = ($result[$item->type] ?? 0) + $item->count;
                    $result[$item->status] = ($result[$item->status] ?? 0) + $item->count;
                }
                return $result;
            });

        return response()->json([
            'success' => true,
            'data' => $stats,
            'daily_stats' => $dailyStats
        ]);
    }

    /**
     * Show statistics page
     */
    public function showStatistics()
    {
        return view('admin.check-in-out.statistics');
    }

    /**
     * Mobile API - Check in/out
     */
    public function mobileCheckInOut(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:bookings,id',
            'type' => 'required|in:check_in,check_out',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $booking = Booking::findOrFail($request->booking_id);

        // Verify user owns this booking
        if ($booking->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền thực hiện check-in/out cho booking này'
            ], 403);
        }

        // Check if already checked in/out
        $existingCheck = CheckInOut::where('user_id', $user->id)
            ->where('booking_id', $request->booking_id)
            ->where('type', $request->type)
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($existingCheck) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã thực hiện ' . ($request->type === 'check_in' ? 'check-in' : 'check-out') . ' cho tour này'
            ], 400);
        }

        $checkInOut = CheckInOut::create([
            'user_id' => $user->id,
            'booking_id' => $request->booking_id,
            'type' => $request->type,
            'check_time' => now(),
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'notes' => $request->notes,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => ($request->type === 'check_in' ? 'Check-in' : 'Check-out') . ' thành công',
            'data' => $checkInOut->load(['booking.tour'])
        ]);
    }

    /**
     * Mobile API - Get user's check-in/out history
     */
    public function mobileHistory(Request $request)
    {
        $user = Auth::user();
        
        $checkInOuts = CheckInOut::where('user_id', $user->id)
            ->with(['booking.tour'])
            ->orderBy('check_time', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $checkInOuts
        ]);
    }
}
