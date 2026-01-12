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
use App\Models\Payment;
use App\Models\Booking;
use App\Models\Category; // Thêm dòng này
use Illuminate\Support\Facades\DB; // Thêm dòng này

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Cấu hình năm lọc
        $availableYears = [2024, 2025, 2026];
        $selectedYear = (int) $request->input('year', Carbon::now()->year);
        $selectedMonth = $request->input('month');

        // Mảng trạng thái chuẩn (Bắt cả Hoa và Thường)
        $validStatuses = ['paid', 'PAID', 'completed', 'COMPLETED'];

        // 2. Lấy thống kê chi tiết (3 ô màu)
        $stats = $this->getDashboardStats($request);
        
        // 3. Xử lý Dữ liệu Biểu đồ (Fix lỗi $revenueData)
        if ($selectedMonth) {
            $dailyRevenue = $this->getDailyRevenue($selectedYear, $selectedMonth, $validStatuses);
            $revenueData = $dailyRevenue;
        } else {
            $revenueData = $this->getRevenueByMonth($selectedYear, $validStatuses);
            $dailyRevenue = [];
        }

        // 4. Lấy Tour phổ biến theo danh mục (Fix lỗi biểu đồ tròn)
        $popularToursByCategory = Category::where('name', '!=', 'Du lịch nước ngoài')
            ->withCount(['tours' => function ($query) {
                $query->where('status', 'active');
            }])
            ->having('tours_count', '>', 0)
            ->orderByDesc('tours_count')
            ->limit(10)
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'count' => $category->tours_count
                ];
            });

        // 5. Tính toán số liệu hiển thị Text (Tổng năm / Tổng tháng)
        $yearlyTotal = Payment::whereYear('created_at', $selectedYear)
                        ->whereIn('status', $validStatuses) 
                        ->where('amount', '>', 0)
                        ->sum('amount');
        
        $monthlyTotal = 0;
        if($selectedMonth) {
            $monthlyTotal = Payment::whereYear('created_at', $selectedYear)
                            ->whereMonth('created_at', $selectedMonth)
                            ->whereIn('status', $validStatuses)
                            ->where('amount', '>', 0)
                            ->sum('amount');
        }

        // 6. Lấy danh sách đặt tour gần đây
        $recent_bookings = Booking::with(['user', 'tour'])
                            ->latest()
                            ->take(5)
                            ->get();

        return view('admin.dashboard', compact(
            'stats', 
            'availableYears', 
            'selectedYear', 
            'selectedMonth',
            'yearlyTotal',
            'monthlyTotal',
            'recent_bookings',
            'revenueData',          // <--- Đã thêm biến này
            'dailyRevenue',         // <--- Đã thêm biến này
            'popularToursByCategory' // <--- Đã thêm biến này
        ));
    }

    /**
     * Hàm tính toán 3 ô chỉ số tài chính
     */
    private function getDashboardStats(Request $request = null): array
    {
        $now = Carbon::now();
        $year = $request ? $request->input('year', $now->year) : $now->year;
        $month = $request ? $request->input('month') : null;

        $financeQuery = Payment::query();
        $financeQuery->whereIn('status', ['completed', 'COMPLETED', 'paid', 'PAID']);
        $financeQuery->whereYear('created_at', $year);
        
        if ($month) {
            $financeQuery->whereMonth('created_at', $month);
        }

        $payments = $financeQuery->get();

        $grossRevenue = $payments->where('amount', '>', 0)->sum('amount');
        $totalRefund = $payments->where('amount', '<', 0)->sum('amount');
        $netIncome = $payments->sum('amount');

        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        return [
            'revenue_this_month' => $grossRevenue,
            'refund_this_month'  => abs($totalRefund),
            'net_income'         => $netIncome,
            'total_tours' => Tour::count(),
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'total_departures' => TourDeparture::count(),
            'upcoming_departures' => TourDeparture::where('departure_date', '>=', $now)
                ->where('departure_date', '<=', $now->copy()->addDays(30))->count(),
            'total_guides' => Guide::count(),
            'active_guides' => Guide::whereNotNull('user_id')->count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'new_customers' => User::where('role', 'customer')
                ->where('created_at', '>=', $startOfMonth)->count(),
            'departures_this_month' => TourDeparture::where('departure_date', '>=', $startOfMonth)
                ->where('departure_date', '<=', $now->copy()->endOfMonth())->count(),
            'departures_last_month' => TourDeparture::where('departure_date', '>=', $startOfLastMonth)
                ->where('departure_date', '<=', $endOfLastMonth)->count(),
            'growth_rate' => 0,
        ];
    }

    /**
     * Hỗ trợ biểu đồ theo tháng
     */
    protected function getRevenueByMonth(int $year, array $validStatuses): array
    {
        $payments = Payment::whereIn('status', $validStatuses)
            ->whereYear('created_at', $year)
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as revenue')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $result = [];
        for ($m = 1; $m <= 12; $m++) {
            $item = $payments->firstWhere('month', $m);
            $result[] = [
                'month' => $m,
                'monthName' => 'Tháng ' . $m,
                'revenue' => $item ? (float) $item->revenue : 0,
            ];
        }
        return $result;
    }

    /**
     * Hỗ trợ biểu đồ theo ngày
     */
    protected function getDailyRevenue(int $year, int $month, array $validStatuses): array
    {
        $payments = Payment::whereIn('status', $validStatuses)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->selectRaw('DAY(created_at) as day, SUM(amount) as revenue')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
        $result = [];

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $item = $payments->firstWhere('day', $d);
            $result[] = [
                'day' => $d,
                'revenue' => $item ? (float) $item->revenue : 0,
            ];
        }
        return $result;
    }

    // Các hàm API giữ nguyên để tránh lỗi JS
    public function getRecentDepartures(): JsonResponse { return response()->json(['success'=>true, 'data'=>[]]); }
    public function getStats(): JsonResponse { return response()->json(['success'=>true, 'data'=>[]]); }
    public function getRevenueData(Request $request): JsonResponse { return response()->json(['success'=>true, 'data'=>[]]); }
    public function getPopularTours(): JsonResponse { return response()->json(['success'=>true, 'data'=>[]]); }
}