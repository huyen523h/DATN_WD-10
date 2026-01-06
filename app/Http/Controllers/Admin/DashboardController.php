<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\TourDeparture;
use App\Models\User;
use App\Models\Guide;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = $this->getDashboardStats();
        
        return view('admin.dashboard', compact('stats'));
    }

    public function getRecentDepartures(): JsonResponse
    {
        try {
            $departures = TourDeparture::with(['tour', 'guide', 'backupGuide'])
                ->where('departure_date', '>=', Carbon::now())
                ->orderBy('departure_date')
                ->limit(10)
                ->get()
                ->map(function ($departure) {
                    return [
                        'id' => $departure->id,
                        'tour_title' => $departure->tour->title ?? 'N/A',
                        'tour_code' => $departure->tour->code ?? null,
                        'departure_date' => $departure->departure_date,
                        'departure_time' => $departure->departure_time,
                        'guide_name' => $departure->guide->name ?? null,
                        'backup_guide_name' => $departure->backupGuide->name ?? null,
                        'preparation_status' => $departure->preparation_status ?? 'pending',
                        'seats_total' => $departure->seats_total,
                        'seats_available' => $departure->seats_available,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $departures
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải dữ liệu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStats(): JsonResponse
    {
        try {
            $stats = $this->getDashboardStats();
            
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải thống kê: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getRevenueData(Request $request): JsonResponse
    {
        try {
            $year = $request->get('year', Carbon::now()->year);
            
            // Giả lập dữ liệu doanh thu (trong thực tế sẽ query từ bookings)
            $revenueData = [];
            for ($month = 1; $month <= 12; $month++) {
                $revenueData[] = [
                    'month' => $month,
                    'revenue' => rand(100, 500) * 1000000, // VNĐ
                    'bookings' => rand(20, 100)
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $revenueData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải dữ liệu doanh thu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getPopularTours(): JsonResponse
    {
        try {
            // Giả lập dữ liệu tour phổ biến (trong thực tế sẽ query từ bookings)
            $popularTours = [
                ['name' => 'Tour Sapa 3N2Đ', 'bookings' => 45, 'percentage' => 30],
                ['name' => 'Hạ Long 2N1Đ', 'bookings' => 38, 'percentage' => 25],
                ['name' => 'Đà Nẵng 4N3Đ', 'bookings' => 30, 'percentage' => 20],
                ['name' => 'Phú Quốc 5N4Đ', 'bookings' => 23, 'percentage' => 15],
                ['name' => 'Các tour khác', 'bookings' => 15, 'percentage' => 10],
            ];

            return response()->json([
                'success' => true,
                'data' => $popularTours
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải dữ liệu tour phổ biến: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getDashboardStats(): array
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        return [
            // Tổng số tour
            'total_tours' => Tour::count(),
            
            // Tổng số lịch khởi hành
            'total_departures' => TourDeparture::count(),
            
            // Lịch khởi hành sắp tới (trong 30 ngày)
            'upcoming_departures' => TourDeparture::where('departure_date', '>=', $now)
                ->where('departure_date', '<=', $now->copy()->addDays(30))
                ->count(),
            
            // Tổng số hướng dẫn viên
            'total_guides' => Guide::count(),
            
            // HDV đang hoạt động (có user account)
            'active_guides' => Guide::whereNotNull('user_id')->count(),
            
            // Tổng số khách hàng
            'total_customers' => User::where('role', 'customer')->count(),
            
            // Khách hàng mới tháng này
            'new_customers' => User::where('role', 'customer')
                ->where('created_at', '>=', $startOfMonth)
                ->count(),
            
            // Departures tháng này
            'departures_this_month' => TourDeparture::where('departure_date', '>=', $startOfMonth)
                ->where('departure_date', '<=', $now->copy()->endOfMonth())
                ->count(),
            
            // Departures tháng trước
            'departures_last_month' => TourDeparture::where('departure_date', '>=', $startOfLastMonth)
                ->where('departure_date', '<=', $endOfLastMonth)
                ->count(),
            
            // Tỷ lệ tăng trưởng
            'growth_rate' => $this->calculateGrowthRate(
                TourDeparture::where('departure_date', '>=', $startOfMonth)->count(),
                TourDeparture::where('departure_date', '>=', $startOfLastMonth)
                    ->where('departure_date', '<=', $endOfLastMonth)->count()
            ),
        ];
    }

    private function calculateGrowthRate($current, $previous): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        
        return round((($current - $previous) / $previous) * 100, 1);
    }
}