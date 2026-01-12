@extends('layouts.admin')

@section('title', 'Quản lý Đặt Tour - Admin')

@section('breadcrumb')
    <li class="breadcrumb-item active">Quản lý Đặt Tour</li>
@endsection

@section('content')
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-dark mb-2">
                        <i class="fas fa-calendar-check text-primary me-2"></i>
                        Quản lý Đặt Tour
                    </h2>
                    <p class="text-muted mb-0">Quản lý tất cả các đặt tour trong hệ thống</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.bookings.manual.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i> Thêm booking thủ công
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Alert -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Cards - Thống kê theo nguồn booking -->
    @php
        // Tính toán thống kê theo nguồn (case-insensitive)
        $totalBookings = $bookings->count();
        $websiteBookings = $bookings
            ->filter(function ($b) {
                return strtolower($b->booking_source ?? 'website') === 'website';
            })
            ->count();
        $saleBookings = $bookings
            ->filter(function ($b) {
                $source = strtolower($b->booking_source ?? '');
                return in_array($source, ['zalo', 'facebook', 'phone']);
            })
            ->count();
        // Doanh thu: không tính booking đã hủy, tính theo ngày khởi hành trong filter
        $totalRevenue = $bookings->where('status', '!=', 'cancelled')->sum('total_amount');
    @endphp
    <div class="row mb-3 g-3">
        <div class="col-lg-3 col-6">
            <div class="card shadow-sm border" style="border-radius:10px;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="flex-shrink-0" style="width:6px; height:48px; background:#0EA5E9; border-radius:4px"></div>
                    <div class="w-100">
                        <div class="small text-muted">Tổng booking</div>
                        <div class="fw-bold fs-4">{{ $totalBookings }}</div>
                    </div>
                    <div class="text-muted"><i class="fas fa-calendar-check fa-lg"></i></div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="card shadow-sm border" style="border-radius:10px;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="flex-shrink-0" style="width:6px; height:48px; background:#38BDF8; border-radius:4px"></div>
                    <div class="w-100">
                        <div class="small text-muted">Từ Website</div>
                        <div class="fw-bold fs-4">{{ $websiteBookings }}</div>
                        <div class="small text-muted">{{ $totalBookings > 0 ? round(($websiteBookings / $totalBookings) * 100, 1) : 0 }}% tổng</div>
                    </div>
                    <div class="text-muted"><i class="fas fa-globe fa-lg"></i></div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="card shadow-sm border" style="border-radius:10px;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="flex-shrink-0" style="width:6px; height:48px; background:#34D399; border-radius:4px"></div>
                    <div class="w-100">
                        <div class="small text-muted">Từ Sale</div>
                        <div class="fw-bold fs-4">{{ $saleBookings }}</div>
                        <div class="small text-muted">Zalo · Facebook · ĐT</div>
                    </div>
                    <div class="text-muted"><i class="fas fa-headset fa-lg"></i></div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="card shadow-sm border" style="border-radius:10px;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="flex-shrink-0" style="width:6px; height:48px; background:#F59E0B; border-radius:4px"></div>
                    <div class="w-100">
                        <div class="small text-muted">Doanh thu</div>
                        <div class="fw-bold fs-4">{{ number_format($totalRevenue, 0, ',', '.') }}đ</div>
                        <div class="small text-muted">Không tính huỷ</div>
                    </div>
                    <div class="text-muted"><i class="fas fa-money-bill-wave fa-lg"></i></div>
                </div>
            </div>
        </div>
    </div>


    <!-- Search and Filter (Tái cấu trúc) -->
    <div class="card mb-2">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.bookings') }}" id="filterForm">
                <!-- Dòng chính: 3 thành phần -->
                <div class="row gy-2 gx-3 align-items-end">
                    <!-- Ô Tìm kiếm -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control" placeholder="Mã Tour, Tên Tour hoặc Tên Khách" value="{{ request('search') }}">
                    </div>

                    <!-- Lọc Trạng thái Tour -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Trạng thái Tour</label>
                        <select name="tour_status" class="form-select" id="tourStatusSelect">
                            <option value="">Tất cả</option>
                            <option value="open" {{ request('tour_status') == 'open' ? 'selected' : '' }}>Đang mở bán / Sắp khởi hành</option>
                            <option value="confirmed" {{ request('tour_status') == 'confirmed' ? 'selected' : '' }}>Đã chốt (Full)</option>
                            <option value="running" {{ request('tour_status') == 'running' ? 'selected' : '' }}>Đang chạy</option>
                            <option value="completed" {{ request('tour_status') == 'completed' ? 'selected' : '' }}>Đã kết thúc</option>
                        </select>
                    </div>

                    <!-- Khoảng ngày khởi hành -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Khoảng ngày khởi hành</label>
                        <input type="text" name="date_range" class="form-control" placeholder="YYYY-MM-DD - YYYY-MM-DD" value="{{ request('date_range') }}">
                    </div>

                    <!-- Nút Lọc nâng cao -->
                    <div class="col-md-2">
                        <div class="btn-group w-100">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i> Tìm
                            </button>
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-filter me-1"></i> Nâng cao
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" style="min-width: 250px;">
                                <li class="px-3 py-2">
                                    <label class="form-label small fw-semibold">Nguồn khách</label>
                                    <select name="source" class="form-select form-select-sm">
                                        <option value="">Tất cả</option>
                                        <option value="website" {{ request('source') == 'website' ? 'selected' : '' }}>Website</option>
                                        <option value="zalo" {{ request('source') == 'zalo' ? 'selected' : '' }}>Zalo</option>
                                        <option value="facebook" {{ request('source') == 'facebook' ? 'selected' : '' }}>Facebook</option>
                                        <option value="phone" {{ request('source') == 'phone' ? 'selected' : '' }}>Điện thoại</option>
                                    </select>
                                </li>
                                <li class="px-3 py-2">
                                    <label class="form-label small fw-semibold">Sale phụ trách</label>
                                    <input type="text" name="sale" class="form-control form-control-sm" placeholder="Tên sale" value="{{ request('sale') }}">
                                </li>
                                <li class="px-3 py-2">
                                    <label class="form-label small fw-semibold">Loại tour</label>
                                    <select name="tour_type" class="form-select form-select-sm">
                                        <option value="">Tất cả</option>
                                        <option value="group" {{ request('tour_type') == 'group' ? 'selected' : '' }}>Đoàn</option>
                                        <option value="join" {{ request('tour_type') == 'join' ? 'selected' : '' }}>Ghép</option>
                                    </select>
                                </li>
                                <li class="px-3 py-2">
                                    <label class="form-label small fw-semibold">Trạng thái thanh toán</label>
                                    <select name="payment_status" class="form-select form-select-sm">
                                        <option value="">Tất cả</option>
                                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                        <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                                        <option value="partial" {{ request('payment_status') == 'partial' ? 'selected' : '' }}>Thanh toán một phần</option>
                                    </select>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li class="px-3 py-2">
                                    <button type="submit" class="btn btn-sm btn-primary w-100">
                                        <i class="fas fa-check me-1"></i> Áp dụng
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Quick Filter Buttons -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="d-flex flex-wrap gap-2">
                        <span class="text-muted small align-self-center me-2">Lọc nhanh:</span>
                        <a href="{{ route('admin.bookings', array_merge(request()->all(), ['quick_filter' => 'upcoming_no_assigned'])) }}" 
                           class="btn btn-sm btn-outline-warning {{ request('quick_filter') == 'upcoming_no_assigned' ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt me-1"></i> Tour sắp khởi hành
                        </a>
                        <a href="{{ route('admin.bookings', array_merge(request()->all(), ['quick_filter' => 'low_capacity'])) }}" 
                           class="btn btn-sm btn-outline-info {{ request('quick_filter') == 'low_capacity' ? 'active' : '' }}">
                            <i class="fas fa-users-slash me-1"></i> Tour chưa đủ khách
                        </a>
                        <a href="{{ route('admin.bookings', array_merge(request()->all(), ['quick_filter' => 'overdue'])) }}" 
                           class="btn btn-sm btn-outline-danger {{ request('quick_filter') == 'overdue' ? 'active' : '' }}">
                            <i class="fas fa-clock me-1"></i> Cảnh báo quá hạn
                        </a>
                        @if(request()->has('quick_filter'))
                            <a href="{{ route('admin.bookings', request()->except('quick_filter')) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Xóa lọc nhanh
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


    @if (isset($groupedBookings) && $groupedBookings->count() > 0)
        <div class="card mb-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:120px">Ngày khởi hành</th>
                                <th>Tour & Khách</th>
                                <th style="width: min-content;" class="text-center">Số lượng (L/T/E)</th>
                                <th style="width:160px" class="text-end">Doanh thu</th>
                                <th style="width:160px">Phụ trách / HDV</th>
                                <th style="width:120px">Trạng thái</th>
                                <th style="width:140px" class="text-end">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($groupedBookings as $group)
                                @php
                                    $departureKey = $group['departure_id'] ?? ($group['date'] ? $group['date']->format('Ymd') : 'no-date');
                                    $totalBookings = $group['count'] ?? collect($group['bookings'] ?? [])->count();
                                    $totalGuests = $group['total_guests'] ?? collect($group['bookings'] ?? [])->sum(function ($item) {
                                        return $item->adults + $item->children + $item->infants;
                                    });
                                    $totalAdults = $group['total_adults'] ?? collect($group['bookings'] ?? [])->sum('adults');
                                    $totalChildren = $group['total_children'] ?? collect($group['bookings'] ?? [])->sum('children');
                                    $totalInfants = $group['total_infants'] ?? collect($group['bookings'] ?? [])->sum('infants');
                                    $totalRevenue = $group['total_amount'] ?? collect($group['bookings'] ?? [])->where('status', '!=', 'cancelled')->sum('total_amount');
                                    $isConfirmed = $group['group_confirmed'] ?? false;
                                    $departureDate = $group['date'] ?? null;
                                    $today = \Carbon\Carbon::today();
                                    $now = \Carbon\Carbon::now();
                                    // cutoff simplified
                                    $cutoffDays = $group['departure']->cutoff_days ?? 3;
                                    $cutoffDate = $departureDate ? $departureDate->copy()->subDays($cutoffDays) : null;
                                    $isAfterCutoff = $cutoffDate ? $now->gt($cutoffDate) : false;
                                    $daysUntilCutoff = $cutoffDate ? $now->diffInDays($cutoffDate, false) : null;
                                    if ($isAfterCutoff) {
                                        $cutoffLabel = 'Quá hạn chốt';
                                        $cutoffClass = 'bg-danger bg-opacity-10 text-danger';
                                    } elseif ($daysUntilCutoff !== null && $daysUntilCutoff <= 2) {
                                        $cutoffLabel = 'Sắp hết hạn';
                                        $cutoffClass = 'bg-warning bg-opacity-10 text-warning';
                                    } else {
                                        $cutoffLabel = 'Chưa chốt';
                                        $cutoffClass = 'bg-secondary bg-opacity-10 text-secondary';
                                    }
                                    // Sử dụng trạng thái từ Controller (đã tính toán real-time)
                                    $groupStatus = $group['group_status'] ?? 'Đang bán';
                                    $statusClass = $group['status_class'] ?? 'bg-success text-success';
                                    $canSuggestConfirm = $group['can_suggest_confirm'] ?? false;
                                    $suggestConfirmLabel = $group['suggest_confirm_label'] ?? '';
                                    $suggestConfirmClass = $group['suggest_confirm_class'] ?? '';
                                    $daysUntilDeparture = $group['days_until_departure'] ?? null;
                                    $diffInDays = $daysUntilDeparture; // Dùng $diffInDays để kiểm tra điều kiện chốt đoàn
                                @endphp
                                <tr>
                                    <td class="text-nowrap">
                                        <div class="fw-semibold">{{ $group['date'] ? $group['date']->format('d/m/Y') : '—' }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $group['tour']->title ?? '—' }}</div>
                                        <div class="small text-muted">{{ $totalBookings }} booking · {{ $totalGuests }} khách</div>
                                    </td>
                                    <td class="text-center" style="width: min-content;">
                                        <div class="text-nowrap">{{ $totalAdults }} / {{ $totalChildren }} / {{ $totalInfants }}</div>
                                    </td>
                                    <td class="text-end fw-bold">{{ number_format($totalRevenue, 0, ',', '.') }}đ</td>
                                    <td>
                                        <div class="fw-semibold">{{ $group['guide']->name ?? ($group['responsible'] ?? '—') }}</div>
                                        @php
                                            // Lấy số chỗ của xe
                                            $vehicleCapacity = null;
                                            if (isset($group['departure']) && $group['departure']->vehicle) {
                                                $vehicleCapacity = $group['departure']->vehicle->capacity ?? null;
                                            }
                                            if (!$vehicleCapacity && isset($group['departure']) && $group['departure']->vehicle_type) {
                                                // Nếu vehicle_type là "29 chỗ", extract số
                                                $vehicleType = $group['departure']->vehicle_type;
                                                if (preg_match('/(\d+)/', $vehicleType, $matches)) {
                                                    $vehicleCapacity = (int)$matches[1];
                                                }
                                            }
                                            if (!$vehicleCapacity && isset($group['vehicle_type'])) {
                                                $vehicleType = $group['vehicle_type'];
                                                if (preg_match('/(\d+)/', $vehicleType, $matches)) {
                                                    $vehicleCapacity = (int)$matches[1];
                                                }
                                            }
                                        @endphp
                                        <div class="small text-muted">
                                            @if($vehicleCapacity)
                                                {{ $totalGuests }} / {{ $vehicleCapacity }} chỗ
                                            @else
                                                {{ $totalGuests }} khách
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill px-3 py-2 fw-bold {{ $statusClass }}" style="display: inline-block; z-index: 1; position: relative;">{{ $groupStatus }}</span>
                                        <div class="mt-1">
                                            <span class="badge rounded-pill px-2 py-1 fw-bold {{ $cutoffClass }}" style="display: inline-block; z-index: 1; position: relative;">{{ $cutoffLabel }}</span>
                                            @if($canSuggestConfirm)
                                                <span class="badge rounded-pill px-2 py-1 fw-bold {{ $suggestConfirmClass }}" style="display: inline-block; z-index: 1; position: relative; margin-left: 4px;">
                                                    <i class="fas fa-info-circle me-1"></i>{{ $suggestConfirmLabel }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center justify-content-end gap-2">
                                            @php
                                                $tourId = $group['tour_id'] ?? null;
                                                $departureDateForRoute = $group['date'] ? $group['date']->format('Y-m-d') : null;
                                            @endphp
                                            @if($tourId && $departureDateForRoute)
                                                <a href="{{ route('admin.bookings.group_list', ['tour_id' => $tourId, 'date' => $departureDateForRoute]) }}" class="btn btn-sm btn-outline-info d-inline-flex align-items-center" title="Xem danh sách khách hàng tổng hợp (cho HDV)">
                                                    <i class="fas fa-users me-1"></i> Danh sách
                                                </a>
                                            @endif
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Điều hành
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end" id="operationMenu-{{ $departureKey }}">
                                                    <li><a class="dropdown-item" href="#" onclick="openAssignGuideModal('{{ $group['date'] ? $group['date']->format('Y-m-d') : 'no-date' }}', {{ $group['tour_id'] ?? 'null' }}, {{ $group['departure_id'] ?? 'null' }})">
                                                        <i class="fas fa-user-tie me-2"></i> Gán HDV
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="openAssignVehicleModal('{{ $group['date'] ? $group['date']->format('Y-m-d') : 'no-date' }}', {{ $group['tour_id'] ?? 'null' }}, {{ $group['departure_id'] ?? ($group['departure']->id ?? 'null') }}, {{ $totalGuests }})">
                                                        <i class="fas fa-bus me-2"></i> Gán xe
                                                    </a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    @if(!$isConfirmed && $diffInDays !== null && $diffInDays > 0 && $diffInDays <= 3)
                                                        <li><a class="dropdown-item text-success fw-bold" href="#" onclick="openConfirmGroupModal('{{ $group['date'] ? $group['date']->format('Y-m-d') : 'no-date' }}', {{ $totalGuests }}, false, '{{ $cutoffDate ? $cutoffDate->format('d/m/Y') : '' }}')">
                                                            <i class="fas fa-check-double me-2"></i> Chốt đoàn
                                                        </a></li>
                                                        <li><hr class="dropdown-divider"></li>
                                                    @endif
                                                    <li><a class="dropdown-item text-danger" href="#" onclick="window.openEndTourModal({{ $group['departure_id'] ?? 'null' }}, '{{ addslashes($group['tour']->title ?? 'N/A') }}', '{{ $group['date'] ? $group['date']->format('d/m/Y') : 'N/A' }}')">
                                                        <i class="fas fa-flag-checkered me-2"></i> Kết thúc tour
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3 d-flex justify-content-end">
                    {{ $groupedBookings->links() }}
                </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="empty-state">
                    <i class="fas fa-search fa-4x text-muted mb-4" style="opacity: 0.5;"></i>
                    <h4 class="text-muted mb-2">Không tìm thấy kết quả</h4>
                    <p class="text-muted mb-4">Không có tour nào phù hợp với bộ lọc hiện tại</p>
                    @if(request()->hasAny(['search', 'tour_status', 'date_range', 'quick_filter', 'source', 'sale', 'payment_status']))
                        <a href="{{ route('admin.bookings') }}" class="btn btn-outline-primary">
                            <i class="fas fa-redo me-1"></i> Xóa tất cả bộ lọc
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif
@endsection

@section('styles')
    <style>
        /* Animate pulse for cutoff warning */
        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.6;
            }
        }

        .animate-pulse {
            animation: pulse 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Card border for after cutoff */
        .departure-card.border-start {
            border-left-width: 4px !important;
        }

        /* Badge styling improvements */
        .badge {
            font-weight: 500;
        }

        /* Fix lỗi lệch cột trong bảng - sử dụng auto layout để tự co lại */
        .table {
            table-layout: auto;
            width: 100%;
            border-collapse: collapse;
        }

        /* Đảm bảo th và td có cùng padding và border - override Bootstrap classes */
        .table th,
        .table td {
            text-align: left;
            vertical-align: middle;
            white-space: normal;
            word-wrap: break-word;
            padding: 12px 15px !important;
            border: none !important;
            box-sizing: border-box;
        }

        /* Override các class Bootstrap padding */
        .table th.py-3,
        .table th.px-3,
        .table td.py-3,
        .table td.px-3 {
            padding: 12px 15px !important;
        }

        /* Xóa hiệu ứng ảo gây lệch */
        .table tr::before,
        .table tr::after {
            content: none !important;
        }

        /* Đảm bảo thead và tbody có cùng cấu trúc */
        .table thead th {
            padding: 12px 15px !important;
            border: none !important;
        }

        .table tbody td {
            padding: 12px 15px !important;
            border: none !important;
        }

        /* Đảm bảo không có margin hoặc spacing khác biệt */
        .table thead,
        .table tbody {
            margin: 0;
            padding: 0;
        }

        /* Cột Số lượng (L/T/E) - nhỏ nhất có thể */
        .table th:nth-child(3),
        .table td:nth-child(3) {
            width: 1% !important;
            white-space: nowrap !important;
            text-align: center !important;
        }

        /* Cột Thao tác - cố định width và căn phải */
        .table th:nth-child(7),
        .table td:nth-child(7) {
            width: 150px !important;
            text-align: right !important;
            white-space: nowrap !important;
        }

        /* Chống lệch do badge hoặc nút */
        .table td span.badge,
        .table td .btn {
            vertical-align: middle !important;
            display: inline-block;
        }

        .card {
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .departure-card .card-header {
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .booking-placeholder {
            border: 1px dashed #d0d7de;
            border-radius: 10px;
            padding: 16px;
            background: #f8fafc;
        }

        .badge {
            border-radius: 8px;
            font-weight: 700 !important;
            padding: 6px 12px !important;
            display: inline-block !important;
            position: relative;
            z-index: 1;
            line-height: 1.4;
        }

        /* Đảm bảo màu chữ tương phản cao cho các badge trạng thái */
        .badge.bg-secondary.text-secondary {
            background-color: #6c757d !important;
            color: #ffffff !important;
        }

        .badge.bg-warning.text-warning {
            background-color: #ffc107 !important;
            color: #000000 !important;
        }

        .badge.bg-primary.text-primary {
            background-color: #0EA5E9 !important;
            color: #ffffff !important;
        }

        .badge.bg-success.text-success {
            background-color: #10b981 !important;
            color: #ffffff !important;
        }

        .badge.bg-danger.bg-opacity-10.text-danger {
            background-color: rgba(220, 53, 69, 0.15) !important;
            color: #dc3545 !important;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .badge.bg-warning.bg-opacity-10.text-warning {
            background-color: rgba(255, 193, 7, 0.15) !important;
            color: #856404 !important;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .badge.bg-secondary.bg-opacity-10.text-secondary {
            background-color: rgba(108, 117, 125, 0.15) !important;
            color: #495057 !important;
            border: 1px solid rgba(108, 117, 125, 0.3);
        }

        /* Badge trạng thái mới - Đang chạy */
        .badge.bg-info.text-info {
            background-color: #0dcaf0 !important;
            color: #000000 !important;
            font-weight: 700 !important;
        }

        /* Badge trạng thái mới - Sắp khởi hành */
        .badge.bg-warning.text-warning {
            background-color: #ffc107 !important;
            color: #000000 !important;
            font-weight: 700 !important;
        }

        /* Badge cảnh báo chốt đoàn */
        .badge.bg-success.bg-opacity-10.text-success {
            background-color: rgba(25, 135, 84, 0.15) !important;
            color: #198754 !important;
            border: 1px solid rgba(25, 135, 84, 0.3);
            font-weight: 700 !important;
        }

        .badge.bg-danger.bg-opacity-10.text-danger {
            background-color: rgba(220, 53, 69, 0.15) !important;
            color: #dc3545 !important;
            border: 1px solid rgba(220, 53, 69, 0.3);
            font-weight: 700 !important;
        }

        .bg-opacity-10 {
            background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
        }

        .text-primary {
            color: #0EA5E9 !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0EA5E9 0%, #38BDF8 100%);
            border: none;
            border-radius: 8px;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0284C7 0%, #0EA5E9 100%);
            transform: translateY(-1px);
        }

        .shadow-sm {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08) !important;
        }

        .border-0 {
            border: none !important;
        }

        .fw-bold {
            font-weight: 700 !important;
        }

        .fw-semibold {
            font-weight: 600 !important;
        }

        .btn-group .btn {
            border-radius: 8px;
            margin: 0 2px;
        }

        .form-select {
            border-radius: 8px;
            font-size: 0.875rem;
        }

        .form-select:focus {
            border-color: #0EA5E9;
            box-shadow: 0 0 0 0.2rem rgba(14, 165, 233, 0.25);
        }

        /* ==========================================
           FILTER STYLES - Tối ưu bộ lọc
           ========================================== */
        .form-select {
            font-size: 0.875rem;
            font-weight: 400;
            min-width: 150px;
        }

        .form-control {
            font-size: 0.875rem;
        }

        /* Quick Filter Buttons */
        .btn-sm.btn-outline-warning.active,
        .btn-sm.btn-outline-info.active,
        .btn-sm.btn-outline-danger.active {
            background-color: var(--bs-warning);
            border-color: var(--bs-warning);
            color: #000;
        }

        .btn-sm.btn-outline-info.active {
            background-color: var(--bs-info);
            border-color: var(--bs-info);
            color: #000;
        }

        .btn-sm.btn-outline-danger.active {
            background-color: var(--bs-danger);
            border-color: var(--bs-danger);
            color: #fff;
        }

        /* Empty State */
        .empty-state {
            padding: 2rem 0;
        }

        .empty-state i {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 0.5;
                transform: translateY(0);
            }
        }

        /* Notification styles */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            z-index: 9999;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification-success {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .notification-error {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .notification i {
            margin-right: 8px;
        }

        /* ==========================================
           FIX DROPDOWN MENU - Không bị nhấp nháy
           ========================================== */
        .btn-group {
            position: relative;
        }

        .dropdown-menu {
            background-color: #fff !important;
            border: 1px solid #ddd !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
            z-index: 9999 !important;
            margin-top: 0 !important;
            padding: 4px 0 !important;
            min-width: 180px;
        }

        .dropdown-menu-end {
            right: 0;
            left: auto;
        }

        /* Fix dropdown không bị che bởi các element khác - Chuyển sang click event */
        .table td .btn-group {
            position: relative !important;
        }

        .table td .dropdown-menu {
            position: absolute !important;
            top: 100% !important;
            left: auto !important;
            right: 0 !important;
            margin-top: 0.125rem !important;
            display: none;
        }

        .table td .dropdown-menu.show {
            display: block !important;
        }

        /* Đảm bảo dropdown toggle chỉ dùng click, không dùng hover */
        .table td .btn-group .dropdown-toggle {
            cursor: pointer;
        }

        .table td .btn-group .dropdown-toggle:focus {
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .dropdown-item {
            padding: 8px 16px !important;
            transition: all 0.2s ease !important;
            cursor: pointer;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa !important;
            color: #212529 !important;
            transform: translateX(2px);
        }

        .dropdown-item.text-danger:hover {
            background-color: #fee !important;
            color: #dc3545 !important;
        }

        .dropdown-divider {
            margin: 4px 0 !important;
        }

        /* Đảm bảo dropdown không bị che bởi các element khác */
        .table td .btn-group {
            position: relative;
        }

        .table td .dropdown-menu {
            position: absolute !important;
            top: 100% !important;
            left: auto !important;
            right: 0 !important;
        }

        /* Fix khoảng cách giữa các nút */
        .table td .d-flex.gap-2 {
            gap: 0.5rem !important;
        }
    </style>
@endsection

@section('scripts')
    @parent
    <script>
        // Cache để lưu bookings đã load (tránh gọi AJAX lại)
        const bookingsCache = {};

        // URL base cho API (dùng prefix thay vì route với placeholder)
        const BOOKINGS_API_BASE = '{{ url('admin/bookings/by-departure') }}/';
        const DEBUG = false;

        document.addEventListener('DOMContentLoaded', function() {

            // Auto-submit khi thay đổi trạng thái tour
            const tourStatusSelect = document.getElementById('tourStatusSelect');
            if (tourStatusSelect) {
                tourStatusSelect.addEventListener('change', function() {
                    document.getElementById('filterForm').submit();
                });
            }

            // Fix dropdown menu - Chuyển hoàn toàn sang click event (không dùng hover)
            document.querySelectorAll('.table td .btn-group .dropdown-toggle').forEach(function(toggleBtn) {
                // Xóa các event listener cũ nếu có
                const newToggleBtn = toggleBtn.cloneNode(true);
                toggleBtn.parentNode.replaceChild(newToggleBtn, toggleBtn);
                
                // Thêm click event mới
                newToggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const dropdownMenu = this.nextElementSibling;
                    if (dropdownMenu && dropdownMenu.classList.contains('dropdown-menu')) {
                        // Đảm bảo menu có z-index cao và background
                        dropdownMenu.style.zIndex = '9999';
                        dropdownMenu.style.backgroundColor = '#fff';
                        
                        // Toggle menu
                        const isShowing = dropdownMenu.classList.contains('show');
                        
                        // Đóng tất cả menu khác
                        document.querySelectorAll('.table td .dropdown-menu.show').forEach(function(menu) {
                            if (menu !== dropdownMenu) {
                                menu.classList.remove('show');
                            }
                        });
                        
                        // Toggle menu hiện tại
                        if (isShowing) {
                            dropdownMenu.classList.remove('show');
                        } else {
                            dropdownMenu.classList.add('show');
                        }
                    }
                });
            });

            // Đóng dropdown khi click bên ngoài
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.table td .btn-group')) {
                    document.querySelectorAll('.table td .dropdown-menu.show').forEach(function(menu) {
                        menu.classList.remove('show');
                    });
                }
            });

            // Ngăn dropdown đóng khi click vào menu items
            document.querySelectorAll('.table td .dropdown-menu').forEach(function(menu) {
                menu.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });

            // Đảm bảo meta[name="csrf-token"] tồn tại trong layout của bạn
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            if (!csrfToken) {
                console.error(
                    'Lỗi: Không tìm thấy CSRF Token. Hãy thêm <meta name="csrf-token" content="{{ csrf_token() }}"> vào layout chính.'
                );
            }

            // Helper functions
            function escapeHtml(string) {
                const div = document.createElement('div');
                div.appendChild(document.createTextNode(string ?? ''));
                return div.innerHTML;
            }

            function formatCurrency(value) {
                const number = Number(value || 0);
                return number.toLocaleString('vi-VN') + ' VNĐ';
            }

            /**
             * TRẠNG THÁI BOOKING (mỗi dòng booking)
             * - pending: Chờ xác nhận
             * - confirmed: Đã xác nhận  
             * - paid: Đã thanh toán
             * - cancelled: Đã hủy
             */
            /**
             * TRẠNG THÁI BOOKING: HOLD | PENDING | CONFIRMED | CANCELLED
             */
            function buildStatusBadge(status) {
                const map = {
                    hold: {
                        class: 'bg-secondary',
                        icon: 'fa-pause-circle',
                        label: 'HOLD'
                    },
                    pending: {
                        class: 'bg-warning text-dark',
                        icon: 'fa-clock',
                        label: 'PENDING'
                    },
                    confirmed: {
                        class: 'bg-success',
                        icon: 'fa-check-circle',
                        label: 'CONFIRMED'
                    },
                    paid: {
                        class: 'bg-info',
                        icon: 'fa-money-bill-wave',
                        label: 'PAID'
                    },
                    cancelled: {
                        class: 'bg-danger',
                        icon: 'fa-times-circle',
                        label: 'CANCELLED'
                    },
                };
                return map[status] || {
                    class: 'bg-secondary',
                    icon: 'fa-question-circle',
                    label: status?.toUpperCase() || 'UNKNOWN'
                };
            }

            /**
             * NGUỒN BOOKING: Website, Zalo Sale, Facebook, Phone
             */
            function buildSourceBadge(source) {
                const s = (source || 'website').toLowerCase();
                const map = {
                    website: {
                        class: 'bg-primary',
                        icon: 'fa-globe',
                        label: '🌐 Website'
                    },
                    zalo: {
                        class: 'bg-info',
                        icon: 'fa-comment-dots',
                        label: '💬 Zalo Sale'
                    },
                    facebook: {
                        class: 'bg-primary',
                        icon: 'fa-facebook',
                        label: '📘 Facebook'
                    },
                    phone: {
                        class: 'bg-success',
                        icon: 'fa-phone',
                        label: '📞 Điện thoại'
                    },
                };
                return map[s] || {
                    class: 'bg-secondary',
                    icon: 'fa-question',
                    label: source || 'Khác'
                };
            }

            /**
             * Build action buttons based on booking status and cutoff
             */
            function buildActionButtons(booking, metadata = {}) {
                let actions = '';
                const status = (booking.status || '').toLowerCase();
                const isAfterCutoff = metadata.isAfterCutoff || false;
                const tourStatus = metadata.tourStatus || 'open';
                const departureStatus = metadata.departureStatus || 'open';

                // Xác định quyền theo trạng thái tour
                const isCompleted = tourStatus === 'completed' || departureStatus === 'completed';
                const isRunning = tourStatus === 'running' || departureStatus === 'running';
                const isConfirmed = tourStatus === 'confirmed' || departureStatus === 'confirmed';

                // Nút Xem chi tiết luôn có
                actions += `<a href="${booking.url}" class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                    <i class="fas fa-eye"></i>
                </a>`;

                if (isCompleted) {
                    // ĐÃ KẾT THÚC: Chỉ xem, đối soát
                    actions += `<span class="ms-1 text-muted" title="Tour đã kết thúc - chỉ xem và đối soát" data-bs-toggle="tooltip">
                        <i class="fas fa-flag-checkered"></i>
                    </span>`;
                } else if (isRunning) {
                    // ĐANG CHẠY: Không cho hủy
                    actions += `<span class="ms-1 text-info" title="Tour đang diễn ra - không thể hủy" data-bs-toggle="tooltip">
                        <i class="fas fa-plane-departure"></i>
                    </span>`;
                } else if (isAfterCutoff || isConfirmed) {
                    // SAU CUTOFF hoặc ĐÃ CHỐT: Chỉ xem, không cho sửa
                    const tooltip = isConfirmed ? 'Đoàn đã chốt - không thể chỉnh sửa' :
                        'Đã quá cutoff - không thể chỉnh sửa';
                    actions += `<span class="ms-1 text-muted" title="${tooltip}" data-bs-toggle="tooltip">
                        <i class="fas fa-lock"></i>
                    </span>`;
                } else {
                    // TRƯỚC CUTOFF: Các action theo status
                    if (status === 'hold') {
                        // HOLD: Xác nhận hoặc Huỷ giữ chỗ
                        actions += `<button class="btn btn-sm btn-outline-success ms-1" onclick="confirmBooking(${booking.id})" title="Xác nhận booking">
                            <i class="fas fa-check"></i>
                        </button>`;
                        actions += `<button class="btn btn-sm btn-outline-danger ms-1" onclick="cancelHoldBooking(${booking.id})" title="Huỷ giữ chỗ">
                            <i class="fas fa-times"></i>
                        </button>`;
                    } else if (status === 'confirmed' || status === 'paid') {
                        // CONFIRMED: In danh sách khách
                        actions += `<button class="btn btn-sm btn-outline-secondary ms-1" onclick="printGuestList(${booking.id})" title="In danh sách khách">
                            <i class="fas fa-print"></i>
                        </button>`;
                    }
                }

                return actions;
            }

            function renderBookingTable(panel, bookings = [], metadata = {}) {
                const isAfterCutoff = metadata.isAfterCutoff || false;
                const tourStatus = metadata.tourStatus || 'open';
                const departureStatus = metadata.departureStatus || 'open';
                const capacityWarning = metadata.capacityWarning || false;
                const placeholder = panel.querySelector('.booking-placeholder');
                const wrapper = panel.querySelector('.booking-table-wrapper');

                if (placeholder) placeholder.classList.add('d-none');
                if (!wrapper) return;

                if (!bookings.length) {
                    wrapper.classList.remove('d-none');
                    wrapper.innerHTML = `<div class="text-center text-muted py-4">
                        <i class="fas fa-info-circle me-2"></i>Chưa có booking nào cho lịch khởi hành này
                    </div>`;
                    return;
                }

                const rows = bookings.map((booking) => {
                    const statusMeta = buildStatusBadge(booking.status);
                    const sourceMeta = buildSourceBadge(booking.booking_source);
                    const totalGuests = (booking.adults || 0) + (booking.children || 0) + (booking
                        .infants || 0);
                    const guestTooltip =
                        `Người lớn: ${booking.adults || 0}, Trẻ em: ${booking.children || 0}, Em bé: ${booking.infants || 0}`;
                    const actionButtons = buildActionButtons(booking, metadata);
                    const saleStaff = booking.sale_staff_name || '-';

                    return `
                        <tr class="${booking.status === 'cancelled' ? 'table-secondary text-muted' : ''}">
                            <td class="px-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-2" style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background-color: #6f42c1; color: white; font-weight: bold; font-size: 12px;">
                                        ${escapeHtml(booking.profile_initial)}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">${escapeHtml(booking.customer_name)}</div>
                                        <small class="text-muted">${escapeHtml(booking.customer_email || booking.customer_phone || '')}</small>
                                        <div><small class="text-primary fw-bold">#${escapeHtml(booking.code)}</small></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 text-center">
                                <span class="badge bg-light text-dark border" 
                                      style="cursor: help; font-size: 14px; padding: 8px 12px;"
                                      data-bs-toggle="tooltip" 
                                      data-bs-placement="top" 
                                      title="${guestTooltip}">
                                    <i class="fas fa-users me-1 text-primary"></i>
                                    <strong>${totalGuests}</strong>
                                </span>
                            </td>
                            <td class="px-3 text-end">
                                <span class="fw-bold text-success">${formatCurrency(booking.total_amount)}</span>
                            </td>
                            <td class="px-3 text-center">
                                <span class="badge ${sourceMeta.class}" style="font-size: 11px;">
                                    ${sourceMeta.label}
                                </span>
                            </td>
                            <td class="px-3 text-center">
                                <small class="text-muted">${escapeHtml(saleStaff)}</small>
                            </td>
                            <td class="px-3 text-center">
                                <span class="badge ${statusMeta.class} px-2 py-1">
                                    <i class="fas ${statusMeta.icon} me-1"></i>${statusMeta.label}
                                </span>
                            </td>
                            <td class="px-3 text-center">
                                <div class="btn-group btn-group-sm">
                                    ${actionButtons}
                                </div>
                            </td>
                        </tr>
                    `;
                }).join('');

                wrapper.classList.remove('d-none');
                wrapper.innerHTML = `
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 py-3 px-3" style="min-width: 200px;">KHÁCH HÀNG</th>
                                    <th class="border-0 py-3 px-3 text-center" style="width: 90px;">SỐ KHÁCH</th>
                                    <th class="border-0 py-3 px-3 text-end" style="width: 130px;">TỔNG TIỀN</th>
                                    <th class="border-0 py-3 px-3 text-center" style="width: 100px;">NGUỒN</th>
                                    <th class="border-0 py-3 px-3 text-center" style="width: 100px;">SALE</th>
                                    <th class="border-0 py-3 px-3 text-center" style="width: 110px;">TRẠNG THÁI</th>
                                    <th class="border-0 py-3 px-3 text-center" style="width: 120px;">THAO TÁC</th>
                                </tr>
                            </thead>
                            <tbody>${rows}</tbody>
                        </table>
                    </div>
                `;

                // Initialize tooltips for guest count
                const tooltipTriggerList = wrapper.querySelectorAll('[data-bs-toggle="tooltip"]');
                tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
            }

            // AJAX: Fetch bookings từ server
            async function fetchBookingsByDeparture(departureId) {
                // Check cache first
                if (bookingsCache[departureId]) {
                    if (DEBUG) console.log('[AJAX] Using cached data for departure:', departureId);
                    return bookingsCache[departureId];
                }

                const url = BOOKINGS_API_BASE + departureId;
                if (DEBUG) console.log('[AJAX] Fetching bookings from:', url);

                try {
                    const response = await fetch(url, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const result = await response.json();
                    if (DEBUG) console.log('[AJAX] Response:', result);

                    if (result.success && result.data) {
                        // Cache the result with all metadata
                        bookingsCache[departureId] = {
                            bookings: result.data,
                            isAfterCutoff: result.is_after_cutoff || false,
                            cutoffDate: result.cutoff_date,
                            daysUntilCutoff: result.days_until_cutoff,
                            departureStatus: result.departure_status || 'open',
                            tourStatus: result.tour_status || 'open',
                            totalGuests: result.total_guests || 0,
                            vehicleCapacity: result.vehicle_capacity,
                            capacityWarning: result.capacity_warning || false
                        };
                        return bookingsCache[departureId];
                    }
                    return {
                        bookings: [],
                        isAfterCutoff: false,
                        departureStatus: 'open',
                        tourStatus: 'open'
                    };
                } catch (error) {
                    console.error('[AJAX] Error fetching bookings:', error);
                    return {
                        bookings: [],
                        isAfterCutoff: false,
                        departureStatus: 'open',
                        tourStatus: 'open'
                    };
                }
            }

            async function loadPanelOnce(panel) {
                if (!panel || panel.dataset.loaded === 'true') return;
                const departureId = panel.dataset.departureKey;

                // Show loading state
                const wrapper = panel.querySelector('.booking-table-wrapper');
                const placeholder = panel.querySelector('.booking-placeholder');
                if (placeholder) {
                    placeholder.innerHTML =
                        '<i class="fas fa-spinner fa-spin me-2"></i> Đang tải danh sách booking...';
                }

                const result = await fetchBookingsByDeparture(departureId);
                renderBookingTable(panel, result.bookings, result);
                panel.dataset.loaded = 'true';
            }

            function setupLazyBookingTables() {
                const toggleButtons = document.querySelectorAll('[data-toggle-bookings]');
                toggleButtons.forEach(btn => {
                    btn.addEventListener('click', async function() {
                        const targetId = this.dataset.target;
                        const departureId = String(this.dataset.departureId);
                        const panel = document.getElementById(targetId) || this.closest(
                            '.departure-card')?.querySelector(`#${targetId}`);
                        if (DEBUG) console.log('[Xem booking] Click', {
                            departureId,
                            targetId,
                            hasPanel: !!panel
                        });
                        if (!panel) return;

                        // Check if already loaded
                        if (panel.dataset.loaded === 'true') {
                            if (DEBUG) console.log('[Xem booking] Already loaded, skipping fetch');
                            return;
                        }

                        // Show loading state
                        const placeholder = panel.querySelector('.booking-placeholder');
                        if (placeholder) {
                            placeholder.innerHTML =
                                '<i class="fas fa-spinner fa-spin me-2"></i> Đang tải danh sách booking...';
                        }

                        // Fetch via AJAX
                        const result = await fetchBookingsByDeparture(departureId);
                        if (DEBUG) console.log('[Xem booking] Loaded bookings', {
                            departureId,
                            count: result.bookings?.length
                        });

                        renderBookingTable(panel, result.bookings, result);
                        panel.dataset.loaded = 'true';
                    });
                });

                // Phòng trường hợp người dùng mở collapse bằng các cách khác
                const collapses = document.querySelectorAll('.collapse[data-departure-key]');
                collapses.forEach(panel => {
                    panel.addEventListener('show.bs.collapse', async function() {
                        await loadPanelOnce(panel);
                    });
                });
            }

            setupLazyBookingTables();

            async function sendRequest(url, method, data = null) {
                try {
                    const options = {
                        method: method,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    };
                    if (data) {
                        options.body = JSON.stringify(data);
                    }

                    const response = await fetch(url, options);
                    const responseData = await response.json();

                    if (!response.ok) {
                        throw new Error(responseData.message || `Lỗi ${response.status}`);
                    }

                    return responseData;

                } catch (error) {
                    console.error('Fetch error:', error);
                    showNotification(error.message || 'Có lỗi xảy ra!', 'error');
                    return null;
                }
            }

            // Xử lý các nút bấm [data-action]
            const actionButtons = document.querySelectorAll('[data-action]');
            actionButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    let bookingId = this.dataset.id;
                    if (!bookingId) {
                        const row = this.closest('tr');
                        if (row) {
                            const idCell = row.querySelector('[data-key="id"]');
                            if (idCell) {
                                bookingId = idCell.textContent.trim().replace('#', '');
                            }
                        }
                    }

                    if (!bookingId) {
                        console.error('Không tìm thấy booking ID cho nút này.');
                        return;
                    }

                    const action = this.dataset.action;
                    switch (action) {
                        case 'view':
                            window.location.href = `/admin/bookings/${bookingId}`;
                            break;

                        case 'status':
                            // Mở Modal chọn trạng thái
                            showStatusModal(bookingId);
                            break;

                        case 'email':
                            // (Giữ nguyên logic của bạn, cần route /send-email)
                            sendBookingEmail(bookingId);
                            break;

                    }
                });
            });

            // HÀM MỚI: Hiển thị Modal
            function showStatusModal(bookingId) {
                const statusOptions = [{
                        action: 'confirm',
                        label: 'Xác nhận đơn',
                        class: 'success',
                        route: `/admin/bookings/${bookingId}/confirm`
                    },
                    {
                        action: 'markAsPaid',
                        label: 'Đánh dấu Đã thanh toán',
                        class: 'primary',
                        route: `/admin/bookings/${bookingId}/mark-as-paid`
                    },
                    {
                        action: 'cancel',
                        label: 'Hủy đơn',
                        class: 'warning',
                        route: `/admin/bookings/${bookingId}/cancel`
                    }
                ];

                let modalHtml = `
            <div class="modal fade" id="statusModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Cập nhật trạng thái booking #${bookingId}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Chọn hành động bạn muốn thực hiện:</p>
                            <div class="list-group">
        `;

                statusOptions.forEach(option => {
                    modalHtml += `
                <button type="button" class="list-group-item list-group-item-action" 
                        onclick="confirmAction('${option.label}', '${option.route}')">
                    <span class="badge bg-${option.class} me-2">${option.label}</span>
                </button>
            `;
                });

                modalHtml += `
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        `;

                // Xóa modal cũ nếu có
                document.getElementById('statusModal')?.remove();
                document.body.insertAdjacentHTML('beforeend', modalHtml);
                const modal = new bootstrap.Modal(document.getElementById('statusModal'));
                modal.show();

                // Xóa modal khỏi DOM khi đóng
                document.getElementById('statusModal').addEventListener('hidden.bs.modal', function() {
                    this.remove();
                });
            }

            window.confirmAction = async function(label, route) {
                if (confirm(`Bạn có chắc chắn muốn: "${label}"?`)) {
                    const data = await sendRequest(route, 'POST');

                    if (data && data.success) {
                        showNotification(data.message, 'success');
                        location.reload(); // Tải lại trang để cập nhật
                    }
                }

                const modal = bootstrap.Modal.getInstance(document.getElementById('statusModal'));
                if (modal) modal.hide();
            }

            async function sendBookingEmail(bookingId) {
                if (confirm('Bạn có chắc chắn muốn gửi email cho khách hàng?')) {
                    // (Lưu ý: Bạn cần tạo route POST /admin/bookings/{id}/send-email trong web.php)
                    const data = await sendRequest(`/admin/bookings/${bookingId}/send-email`, 'POST');
                    if (data && data.success) {
                        showNotification(data.message, 'success');
                    }
                }
            }


            function showNotification(message, type) {
                const notification = document.createElement('div');
                notification.className = `notification notification-${type}`;
                notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        `;

                document.body.appendChild(notification);
                setTimeout(() => notification.classList.add('show'), 100);

                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }
        });
    </script>
    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof bootstrap !== 'undefined') {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
        });

        // Generate Invoice PDF
        async function generateInvoice(bookingId, buttonElement = null) {
            let button = null;
            let originalContent = null;

            try {
                button = buttonElement || event.target.closest('button');
                originalContent = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                button.disabled = true;

                if (DEBUG) console.log('Generating invoice for booking:', bookingId);

                // First test simple debug route
                const debugResponse = await fetch(`/debug-invoice-simple/${bookingId}`);
                const debugData = await debugResponse.json();
                if (DEBUG) console.log('Debug response:', debugData);

                if (!debugData.success) {
                    throw new Error('Debug failed: ' + debugData.message);
                }

                const response = await fetch(`/web/invoices/booking/${bookingId}/pdf`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                });

                if (DEBUG) console.log('Response status:', response.status);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                if (DEBUG) console.log('Response data:', data);

                if (data.success && data.data && data.data.download_url) {
                    // Open PDF in new tab
                    const newWindow = window.open(data.data.download_url, '_blank');

                    if (newWindow) {
                        showAlert('success', 'PDF hóa đơn đã được tạo thành công!');
                    } else {
                        showAlert('warning', 'Popup bị chặn. Vui lòng cho phép popup và thử lại.');
                    }
                } else {
                    showAlert('danger', 'Lỗi: ' + (data.message || 'Không thể tạo PDF'));
                }
            } catch (error) {
                console.error('Error generating invoice:', error);
                showAlert('danger', 'Lỗi: ' + error.message);
            } finally {
                if (button && originalContent) {
                    button.innerHTML = originalContent;
                    button.disabled = false;
                }
            }
        }


        // Download Invoice PDF
        async function downloadInvoice(bookingId) {
            let button = null;
            let originalContent = null;

            try {
                button = event.target.closest('button');
                originalContent = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                button.disabled = true;

                const response = await fetch(`/web/invoices/booking/${bookingId}/pdf`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        // Download the PDF file
                        const link = document.createElement('a');
                        link.href = data.data.download_url;
                        link.download = data.data.file_name;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);

                        showAlert('success', 'PDF hóa đơn đã được tải xuống!');
                    } else {
                        showAlert('danger', 'Lỗi: ' + data.message);
                    }
                } else {
                    const errorText = await response.text();
                    showAlert('danger', 'Lỗi: ' + errorText);
                }
            } catch (error) {
                showAlert('danger', 'Lỗi: ' + error.message);
            } finally {
                if (button && originalContent) {
                    button.innerHTML = originalContent;
                    button.disabled = false;
                }
            }
        }

        // Show alert message
        function showAlert(type, message) {
            const alertContainer = document.createElement('div');
            alertContainer.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertContainer.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertContainer.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : type === 'info' ? 'info-circle' : 'exclamation-circle'} me-2"></i>
                <span>${message}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

            document.body.appendChild(alertContainer);

            setTimeout(() => {
                if (alertContainer.parentNode) {
                    alertContainer.remove();
                }
            }, 5000);
        }

        // B2: Chốt đoàn
        function openConfirmGroupModal(departureDate, totalGuests, isAdminOverride = false, cutoffDate = '') {
            const overrideWarning = isAdminOverride ? `
                <div class="alert alert-danger mb-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>⚠️ ADMIN OVERRIDE - CẢNH BÁO!</strong>
                    <ul class="mb-0 mt-2">
                        <li>Tour đã quá cutoff (${cutoffDate || 'N/A'})</li>
                        <li>Hành động này sẽ được ghi log vào hệ thống</li>
                        <li>Chỉ sử dụng trong trường hợp đặc biệt</li>
                    </ul>
                </div>
            ` : '';

            const modalHtml = `
                <div class="modal fade" id="confirmGroupModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header ${isAdminOverride ? 'bg-danger text-white' : ''}">
                                <h5 class="modal-title">
                                    <i class="fas ${isAdminOverride ? 'fa-unlock' : 'fa-check-double'} me-2"></i>
                                    ${isAdminOverride ? 'Admin Override - Chốt đoàn' : 'Chốt đoàn'}
                                </h5>
                                <button type="button" class="btn-close ${isAdminOverride ? 'btn-close-white' : ''}" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="confirmGroupForm">
                                <div class="modal-body">
                                    ${overrideWarning}
                                    <input type="hidden" name="departure_date" value="${departureDate}">
                                    <div class="mb-3">
                                        <label class="form-label">Tổng số khách hiện tại: <strong>${totalGuests}</strong></label>
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirmed_guests_count" class="form-label">Số khách chốt đoàn *</label>
                                        <input type="number" class="form-control" id="confirmed_guests_count" name="confirmed_guests_count" min="1" value="${totalGuests}" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <button type="submit" class="btn ${isAdminOverride ? 'btn-danger' : 'btn-success'}">
                                        <i class="fas fa-check me-1"></i> ${isAdminOverride ? 'Xác nhận Override' : 'Chốt đoàn'}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHtml);
            const modal = new bootstrap.Modal(document.getElementById('confirmGroupModal'));
            modal.show();

            document.getElementById('confirmGroupForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                try {
                    const response = await fetch('{{ route('admin.bookings.confirm-group') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const data = await response.json();
                    if (data.success) {
                        showAlert('success', data.message);
                        modal.hide();
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showAlert('danger', data.message || 'Có lỗi xảy ra');
                    }
                } catch (error) {
                    showAlert('danger', 'Lỗi: ' + error.message);
                }
            });

            document.getElementById('confirmGroupModal').addEventListener('hidden.bs.modal', function() {
                this.remove();
            });
        }

        // B3: Gán HDV (từ dropdown Điều hành) - CẢI THIỆN VỚI THÔNG TIN CHI TIẾT
        async function openAssignGuideModal(departureDate, tourId, departureId = null) {
            // Hiển thị modal với loading state
            const modalHtml = `
                <div class="modal fade" id="assignGuideModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-info text-white">
                                <h5 class="modal-title"><i class="fas fa-user-tie me-2"></i>Gán hướng dẫn viên</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="assignGuideForm">
                                <div class="modal-body">
                                    <input type="hidden" name="departure_date" value="${departureDate}">
                                    <input type="hidden" name="tour_id" value="${tourId}">
                                    <input type="hidden" name="departure_id" value="${departureId || ''}">
                                    <div class="mb-3">
                                        <label for="guide_id" class="form-label">Chọn hướng dẫn viên *</label>
                                        <select class="form-select" id="guide_id" name="guide_id" required>
                                            <option value="">Đang tải danh sách...</option>
                                        </select>
                                        <small class="text-muted">Chỉ hiển thị các HDV chưa được gán cho tour khác cùng ngày.</small>
                                    </div>
                                    
                                    <!-- THÔNG TIN CHI TIẾT HDV -->
                                    <div id="guideInfoCard" class="card bg-light d-none mb-3">
                                        <div class="card-body">
                                            <h6 class="card-title mb-3"><i class="fas fa-info-circle me-2"></i>Thông tin HDV</h6>
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <small class="text-muted d-block">Số điện thoại</small>
                                                    <strong id="guidePhone" class="d-block">-</strong>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="text-muted d-block">Email</small>
                                                    <strong id="guideEmail" class="d-block">-</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- GHI CHÚ CHO HDV -->
                                    <div class="mb-3">
                                        <label for="guide_notes" class="form-label">
                                            <i class="fas fa-sticky-note me-1"></i>Ghi chú cho HDV
                                        </label>
                                        <textarea class="form-control" id="guide_notes" name="guide_notes" rows="3" 
                                                  placeholder="Nhập ghi chú đặc biệt cho HDV (ví dụ: Đón khách tại sân bay, Lưu ý về dị ứng thực phẩm...)"></textarea>
                                        <small class="text-muted">Ghi chú này sẽ được gửi cho HDV trước khi tour khởi hành.</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <button type="submit" class="btn btn-info">
                                        <i class="fas fa-check me-1"></i> Xác nhận gán HDV
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHtml);
            const modal = new bootstrap.Modal(document.getElementById('assignGuideModal'));
            modal.show();

            // Gọi API để lấy danh sách HDV có sẵn
            try {
                const availableGuidesUrl = '{{ route('admin.bookings.available-guides') }}';
                const url = availableGuidesUrl + '?departure_date=' + encodeURIComponent(departureDate) + '&tour_id=' +
                    encodeURIComponent(tourId);
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                const selectElement = document.getElementById('guide_id');
                const guidesData = {}; // Lưu thông tin chi tiết HDV

                if (data.success && data.data.length > 0) {
                    let optionsHtml = '<option value="">-- Chọn hướng dẫn viên --</option>';
                    data.data.forEach(guide => {
                        optionsHtml +=
                            `<option value="${guide.id}" data-phone="${guide.phone || ''}" data-email="${guide.email || ''}">${guide.name} (${guide.email || 'N/A'})</option>`;
                        guidesData[guide.id] = {
                            phone: guide.phone || 'Chưa có',
                            email: guide.email || 'Chưa có'
                        };
                    });
                    selectElement.innerHTML = optionsHtml;

                    // Event listener để hiển thị thông tin chi tiết khi chọn HDV
                    selectElement.addEventListener('change', function() {
                        const selectedId = this.value;
                        const guideInfoCard = document.getElementById('guideInfoCard');
                        if (selectedId && guidesData[selectedId]) {
                            document.getElementById('guidePhone').textContent = guidesData[selectedId].phone;
                            document.getElementById('guideEmail').textContent = guidesData[selectedId].email;
                            guideInfoCard.classList.remove('d-none');
                        } else {
                            guideInfoCard.classList.add('d-none');
                        }
                    });
                } else {
                    selectElement.innerHTML = '<option value="">Không có HDV nào có sẵn</option>';
                    selectElement.disabled = true;
                }
            } catch (error) {
                console.error('Error loading guides:', error);
                document.getElementById('guide_id').innerHTML = '<option value="">Lỗi khi tải danh sách HDV</option>';
            }

            document.getElementById('assignGuideForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                try {
                    const response = await fetch('{{ route('admin.bookings.assign-guide') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const data = await response.json();
                    if (data.success) {
                        showAlert('success', data.message);
                        modal.hide();
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showAlert('danger', data.message || 'Có lỗi xảy ra');
                    }
                } catch (error) {
                    showAlert('danger', 'Lỗi: ' + error.message);
                }
            });

            document.getElementById('assignGuideModal').addEventListener('hidden.bs.modal', function() {
                this.remove();
            });
        }

        // B4: Gán xe (từ dropdown Điều hành) - CẢI THIỆN VỚI THÔNG TIN CHI TIẾT VÀ CẢNH BÁO SỨC CHỨA
        async function openAssignVehicleModal(departureDate, tourId, departureId = null, initialTotalGuests = 0) {
            // Lấy thông tin tổng số khách hiện tại
            // Ưu tiên dùng giá trị truyền vào, nếu không có thì fetch từ API
            let totalGuests = parseInt(initialTotalGuests) || 0;

            // Nếu không có totalGuests ban đầu và có departureId, fetch từ API
            if (totalGuests === 0 && departureId && departureId !== 'null') {
                try {
                    const bookingsResult = await fetchBookingsByDeparture(departureId);
                    if (DEBUG) console.log('[Gán xe] Bookings result:', bookingsResult);
                    if (bookingsResult) {
                        // Ưu tiên lấy từ metadata
                        if (bookingsResult.total_guests !== undefined && bookingsResult.total_guests > 0) {
                            totalGuests = parseInt(bookingsResult.total_guests) || 0;
                        } else if (bookingsResult.bookings && Array.isArray(bookingsResult.bookings)) {
                            // Tính lại từ danh sách booking nếu không có trong metadata
                            totalGuests = bookingsResult.bookings.reduce((sum, booking) => {
                                return sum + (parseInt(booking.adults) || 0) + (parseInt(booking.children) ||
                                    0);
                            }, 0);
                        }
                    }
                    if (DEBUG) console.log('[Gán xe] Total guests from API:', totalGuests);
                } catch (e) {
                    console.warn('Could not fetch total guests:', e);
                }
            }

            if (DEBUG) console.log('[Gán xe] Final total guests:', totalGuests, 'Initial:', initialTotalGuests, 'DepartureId:',
                departureId);

            // Hiển thị modal với loading state
            const modalHtml = `
                <div class="modal fade" id="assignVehicleModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-warning">
                                <h5 class="modal-title"><i class="fas fa-bus me-2"></i>Gán xe</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="assignVehicleForm">
                                <div class="modal-body">
                                    <input type="hidden" name="departure_date" value="${departureDate}">
                                    <input type="hidden" name="tour_id" value="${tourId}">
                                    <input type="hidden" name="departure_id" value="${departureId || ''}">
                                    
                                    <!-- THÔNG TIN TỔNG SỐ KHÁCH -->
                                    <div class="alert alert-info mb-3">
                                        <i class="fas fa-users me-2"></i>
                                        <strong>Tổng số khách hiện tại:</strong> <span id="currentTotalGuests">${totalGuests}</span> khách
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="vehicle_id" class="form-label">Chọn xe *</label>
                                        <select class="form-select" id="vehicle_id" name="vehicle_id" required>
                                            <option value="">Đang tải danh sách...</option>
                                        </select>
                                        <small class="text-muted">Chỉ hiển thị các xe chưa được gán cho tour khác cùng ngày.</small>
                                    </div>
                                    
                                    <!-- THÔNG TIN CHI TIẾT XE -->
                                    <div id="vehicleInfoCard" class="card bg-light d-none mb-3">
                                        <div class="card-body">
                                            <h6 class="card-title mb-3"><i class="fas fa-info-circle me-2"></i>Thông tin xe</h6>
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <small class="text-muted d-block">Tài xế</small>
                                                    <strong id="vehicleDriver" class="d-block">-</strong>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="text-muted d-block">Số điện thoại tài xế</small>
                                                    <strong id="vehicleDriverPhone" class="d-block">-</strong>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="text-muted d-block">Công ty xe</small>
                                                    <strong id="vehicleCompany" class="d-block">-</strong>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="text-muted d-block">Sức chứa</small>
                                                    <strong id="vehicleCapacity" class="d-block">-</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- CẢNH BÁO SỨC CHỨA -->
                                    <div id="capacityWarning" class="alert alert-danger d-none mb-3">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>Cảnh báo!</strong>
                                        <div id="capacityWarningText"></div>
                                    </div>
                                    
                                    <p class="text-muted mb-0"><small>Chỉ hiển thị các xe chưa được gán cho tour khác cùng ngày.</small></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <button type="submit" class="btn btn-warning" id="confirmVehicleBtn">
                                        <i class="fas fa-check me-1"></i> Xác nhận gán xe
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHtml);
            const modal = new bootstrap.Modal(document.getElementById('assignVehicleModal'));
            modal.show();

            // Gọi API để lấy danh sách xe có sẵn
            try {
                const availableVehiclesUrl = '{{ route('admin.bookings.available-vehicles') }}';
                const url = availableVehiclesUrl + '?departure_date=' + encodeURIComponent(departureDate) +
                    '&tour_id=' + encodeURIComponent(tourId);
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                const selectElement = document.getElementById('vehicle_id');
                const vehiclesData = {}; // Lưu thông tin chi tiết xe

                if (data.success && data.data.length > 0) {
                    let optionsHtml = '<option value="">-- Chọn xe --</option>';
                    data.data.forEach(vehicle => {
                        const label = vehicle.label ||
                            `${vehicle.license_plate || 'N/A'} - ${vehicle.vehicle_type || 'N/A'} chỗ`;
                        const capacity = parseInt(vehicle.capacity || vehicle.seats || 0);
                        optionsHtml += `<option value="${vehicle.id}" 
                            data-driver="${vehicle.driver_name || ''}" 
                            data-driver-phone="${vehicle.driver_phone || ''}" 
                            data-company="${vehicle.bus_company || vehicle.company || ''}" 
                            data-capacity="${capacity}">${label}</option>`;
                        vehiclesData[vehicle.id] = {
                            driver: vehicle.driver_name || 'Chưa có',
                            driverPhone: vehicle.driver_phone || 'Chưa có',
                            company: vehicle.bus_company || vehicle.company || 'Chưa có',
                            capacity: capacity
                        };
                    });
                    selectElement.innerHTML = optionsHtml;

                    // Cập nhật lại totalGuests nếu có departureId và chưa có giá trị (lấy từ cache hoặc tính lại)
                    if (departureId && departureId !== 'null' && totalGuests === 0) {
                        try {
                            const bookingsResult = await fetchBookingsByDeparture(departureId);
                            if (bookingsResult) {
                                let updatedTotalGuests = 0;
                                if (bookingsResult.total_guests !== undefined && bookingsResult.total_guests > 0) {
                                    updatedTotalGuests = parseInt(bookingsResult.total_guests) || 0;
                                } else if (bookingsResult.bookings && Array.isArray(bookingsResult.bookings)) {
                                    updatedTotalGuests = bookingsResult.bookings.reduce((sum, booking) => {
                                        return sum + (parseInt(booking.adults) || 0) + (parseInt(booking
                                            .children) || 0);
                                    }, 0);
                                }
                                if (updatedTotalGuests > 0) {
                                    document.getElementById('currentTotalGuests').textContent = updatedTotalGuests;
                                    totalGuests = updatedTotalGuests;
                                    if (DEBUG) console.log('[Gán xe] Updated total guests from API:', updatedTotalGuests);
                                }
                            }
                        } catch (e) {
                            console.warn('Could not update total guests:', e);
                        }
                    }

                    // Event listener để hiển thị thông tin chi tiết và cảnh báo khi chọn xe
                    selectElement.addEventListener('change', function() {
                        const selectedId = this.value;
                        const vehicleInfoCard = document.getElementById('vehicleInfoCard');
                        const capacityWarning = document.getElementById('capacityWarning');
                        const confirmBtn = document.getElementById('confirmVehicleBtn');

                        if (DEBUG) console.log('[Gán xe] Vehicle selected:', selectedId, vehiclesData[selectedId]);

                        if (selectedId && vehiclesData[selectedId]) {
                            const vehicle = vehiclesData[selectedId];
                            document.getElementById('vehicleDriver').textContent = vehicle.driver;
                            document.getElementById('vehicleDriverPhone').textContent = vehicle.driverPhone;
                            document.getElementById('vehicleCompany').textContent = vehicle.company;
                            document.getElementById('vehicleCapacity').textContent = vehicle.capacity + ' chỗ';
                            vehicleInfoCard.classList.remove('d-none');

                            // Kiểm tra sức chứa - ĐẢM BẢO SO SÁNH ĐÚNG KIỂU DỮ LIỆU
                            const currentGuestsText = document.getElementById('currentTotalGuests').textContent
                                .trim();
                            const currentGuests = parseInt(currentGuestsText) || 0;
                            const vehicleCapacity = parseInt(vehicle.capacity) || 0;

                            if (DEBUG) console.log('[Gán xe] Capacity check:', {
                                currentGuests,
                                vehicleCapacity,
                                currentGuestsText,
                                vehicleCapacityRaw: vehicle.capacity
                            });

                            if (vehicleCapacity > 0 && currentGuests > vehicleCapacity) {
                                const excess = currentGuests - vehicleCapacity;
                                document.getElementById('capacityWarningText').innerHTML =
                                    `Số khách (<strong>${currentGuests}</strong>) vượt quá sức chứa xe (<strong>${vehicleCapacity} chỗ</strong>). Vượt <strong class="text-danger">${excess} khách</strong>. Vui lòng xác nhận lại!`;
                                capacityWarning.classList.remove('d-none');
                                confirmBtn.classList.remove('btn-warning');
                                confirmBtn.classList.add('btn-danger');
                                confirmBtn.innerHTML =
                                    '<i class="fas fa-exclamation-triangle me-1"></i> Xác nhận gán xe (Vượt sức chứa)';
                            } else {
                                capacityWarning.classList.add('d-none');
                                confirmBtn.classList.remove('btn-danger');
                                confirmBtn.classList.add('btn-warning');
                                confirmBtn.innerHTML = '<i class="fas fa-check me-1"></i> Xác nhận gán xe';
                            }
                        } else {
                            vehicleInfoCard.classList.add('d-none');
                            capacityWarning.classList.add('d-none');
                            confirmBtn.classList.remove('btn-danger');
                            confirmBtn.classList.add('btn-warning');
                            confirmBtn.innerHTML = '<i class="fas fa-check me-1"></i> Xác nhận gán xe';
                        }
                    });
                } else {
                    selectElement.innerHTML = '<option value="">Không có xe nào có sẵn</option>';
                    selectElement.disabled = true;
                }
            } catch (error) {
                console.error('Error loading vehicles:', error);
                document.getElementById('vehicle_id').innerHTML = '<option value="">Lỗi khi tải danh sách xe</option>';
            }

            document.getElementById('assignVehicleForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                try {
                    const response = await fetch('{{ route('admin.bookings.assign-vehicle') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const data = await response.json();
                    if (data.success) {
                        showAlert('success', data.message);
                        modal.hide();
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showAlert('danger', data.message || 'Có lỗi xảy ra');
                    }
                } catch (error) {
                    showAlert('danger', 'Lỗi: ' + error.message);
                }
            });

            document.getElementById('assignVehicleModal').addEventListener('hidden.bs.modal', function() {
                this.remove();
            });
        }

        // B5: Gửi thông tin trước tour
        function openSendPreTourInfoModal(departureDate) {
            const modalHtml = `
                <div class="modal fade" id="sendPreTourInfoModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Gửi thông tin trước tour</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="sendPreTourInfoForm">
                                <div class="modal-body">
                                    <input type="hidden" name="departure_date" value="${departureDate}">
                                    <div class="mb-3">
                                        <label for="message" class="form-label">Nội dung thông báo</label>
                                        <textarea class="form-control" id="message" name="message" rows="4" placeholder="Nhập thông tin cần gửi cho khách hàng..."></textarea>
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="send_email" name="send_email" value="1" checked>
                                        <label class="form-check-label" for="send_email">Gửi qua email</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <button type="submit" class="btn btn-primary">Gửi thông tin</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHtml);
            const modal = new bootstrap.Modal(document.getElementById('sendPreTourInfoModal'));
            modal.show();

            document.getElementById('sendPreTourInfoForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                try {
                    const response = await fetch('{{ route('admin.bookings.send-pre-tour-info') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const data = await response.json();
                    if (data.success) {
                        showAlert('success', data.message);
                        modal.hide();
                    } else {
                        showAlert('danger', data.message || 'Có lỗi xảy ra');
                    }
                } catch (error) {
                    showAlert('danger', 'Lỗi: ' + error.message);
                }
            });

            document.getElementById('sendPreTourInfoModal').addEventListener('hidden.bs.modal', function() {
                this.remove();
            });
        }

        // B6: Ghi chú điều hành (từ dropdown Điều hành)
        function openOperationNoteModal(departureDate, departureId = null) {
            const modalHtml = `
                <div class="modal fade" id="operationNoteModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-secondary text-white">
                                <h5 class="modal-title"><i class="fas fa-sticky-note me-2"></i>Ghi chú điều hành</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="operationNoteForm">
                                <div class="modal-body">
                                    <input type="hidden" name="departure_date" value="${departureDate}">
                                    <input type="hidden" name="departure_id" value="${departureId || ''}">
                                    <div class="mb-3">
                                        <label for="operation_note" class="form-label">Ghi chú cho đoàn</label>
                                        <textarea class="form-control" id="operation_note" name="operation_note" rows="5" 
                                                  placeholder="Nhập ghi chú điều hành cho đoàn này...&#10;Ví dụ:&#10;- Điểm đón khách: ...&#10;- Lưu ý đặc biệt: ...&#10;- Yêu cầu ăn uống: ..."></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="internal_note" class="form-label">Ghi chú nội bộ (chỉ admin thấy)</label>
                                        <textarea class="form-control" id="internal_note" name="internal_note" rows="3" 
                                                  placeholder="Ghi chú nội bộ cho team điều hành..."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Lưu ghi chú
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHtml);
            const modal = new bootstrap.Modal(document.getElementById('operationNoteModal'));
            modal.show();

            document.getElementById('operationNoteForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                // TODO: Implement API endpoint for saving operation notes
                // For now, just show success message
                showAlert('info', 'Chức năng lưu ghi chú đang được phát triển');
                modal.hide();

                /* Uncomment when API is ready:
                try {
                    const response = await fetch('/admin/bookings/save-operation-note', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    if (data.success) {
                        showAlert('success', data.message);
                        modal.hide();
                    } else {
                        showAlert('danger', data.message || 'Có lỗi xảy ra');
                    }
                } catch (error) {
                    showAlert('danger', 'Lỗi: ' + error.message);
                }
                */
            });

            document.getElementById('operationNoteModal').addEventListener('hidden.bs.modal', function() {
                this.remove();
            });
        }

        // ==========================================
        // BOOKING ACTIONS (từ table booking)
        // ==========================================

        /**
         * Xác nhận booking (chuyển từ HOLD sang PENDING hoặc CONFIRMED)
         */
        async function confirmBooking(bookingId) {
            if (!confirm('Bạn có chắc muốn xác nhận booking này?')) return;

            try {
                const response = await fetch(`{{ url('admin/bookings') }}/${bookingId}/confirm`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                if (data.success) {
                    showAlert('success', data.message || 'Đã xác nhận booking thành công!');
                    // Clear cache and reload
                    Object.keys(bookingsCache).forEach(key => delete bookingsCache[key]);
                    location.reload();
                } else {
                    showAlert('danger', data.message || 'Có lỗi xảy ra khi xác nhận booking');
                }
            } catch (error) {
                console.error('Error confirming booking:', error);
                showAlert('danger', 'Lỗi: ' + error.message);
            }
        }

        /**
         * Huỷ giữ chỗ (booking HOLD)
         */
        async function cancelHoldBooking(bookingId) {
            if (!confirm('Bạn có chắc muốn huỷ giữ chỗ này? Booking sẽ chuyển sang trạng thái CANCELLED.')) return;

            try {
                const response = await fetch(`{{ url('admin/bookings') }}/${bookingId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                if (data.success) {
                    showAlert('success', data.message || 'Đã huỷ giữ chỗ thành công!');
                    // Clear cache and reload
                    Object.keys(bookingsCache).forEach(key => delete bookingsCache[key]);
                    location.reload();
                } else {
                    showAlert('danger', data.message || 'Có lỗi xảy ra khi huỷ giữ chỗ');
                }
            } catch (error) {
                console.error('Error cancelling hold:', error);
                showAlert('danger', 'Lỗi: ' + error.message);
            }
        }

        /**
         * In danh sách khách của booking
         */
        function printGuestList(bookingId) {
            window.open(`{{ url('admin/bookings') }}/${bookingId}/print-guests`, '_blank');
        }
    });

    /**
     * Mở modal xác nhận kết thúc tour - Global function để có thể gọi từ onclick
     */
    window.openEndTourModal = function(departureId, tourTitle = 'N/A', departureDate = 'N/A') {
            if (!departureId || departureId === 'null') {
                showAlert('warning', 'Không tìm thấy thông tin departure');
                return;
            }

            const modalHtml = `
                <div class="modal fade" id="endTourModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">
                                    <i class="fas fa-flag-checkered me-2"></i>
                                    Xác nhận kết thúc tour
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="endTourForm">
                                <div class="modal-body">
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>Cảnh báo!</strong> Hành động này sẽ đánh dấu tour đã kết thúc và không thể hoàn tác.
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Thông tin tour:</label>
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <p class="mb-1"><strong>Tour:</strong> ${tourTitle}</p>
                                                <p class="mb-0"><strong>Ngày khởi hành:</strong> ${departureDate}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="departure_id" value="${departureId}">
                                    <div class="mb-3">
                                        <label for="end_tour_notes" class="form-label">Ghi chú (tùy chọn)</label>
                                        <textarea class="form-control" id="end_tour_notes" name="notes" rows="3" 
                                                  placeholder="Nhập ghi chú về việc kết thúc tour (ví dụ: Tour đã hoàn thành thành công, Khách đã về an toàn...)"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-check me-1"></i> Xác nhận kết thúc
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            `;

            // Xóa modal cũ nếu có
            document.getElementById('endTourModal')?.remove();
            
            // Thêm modal mới vào DOM
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            
            // Hiển thị modal
            const modal = new bootstrap.Modal(document.getElementById('endTourModal'));
            modal.show();

            // Xử lý submit form
            document.getElementById('endTourForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                
                try {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Đang xử lý...';

                    const response = await fetch('{{ route('admin.bookings.end-tour') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const data = await response.json();
                    if (data.success) {
                        showAlert('success', data.message || 'Tour đã được kết thúc thành công!');
                        modal.hide();
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showAlert('danger', data.message || 'Có lỗi xảy ra khi kết thúc tour');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                } catch (error) {
                    showAlert('danger', 'Lỗi: ' + error.message);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });

            // Xóa modal khỏi DOM khi đóng
            document.getElementById('endTourModal').addEventListener('hidden.bs.modal', function() {
                this.remove();
            });
    };
    </script>
@endsection
