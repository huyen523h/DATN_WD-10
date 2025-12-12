@extends('layouts.guide')

@section('title', 'Check-in/Check-out - Hướng dẫn viên')
@section('page-title', 'Check-in/Check-out')

@section('content')
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="guide-stat-card">
                <div class="guide-stat-icon primary">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="guide-stat-value">{{ $stats['total_today'] }}</div>
                <p class="guide-stat-label">Tổng hôm nay</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="guide-stat-card">
                <div class="guide-stat-icon success">
                    <i class="fas fa-sign-in-alt"></i>
                </div>
                <div class="guide-stat-value">{{ $stats['check_ins_today'] }}</div>
                <p class="guide-stat-label">Check-in hôm nay</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="guide-stat-card">
                <div class="guide-stat-icon warning">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <div class="guide-stat-value">{{ $stats['check_outs_today'] }}</div>
                <p class="guide-stat-label">Check-out hôm nay</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="guide-stat-card">
                <div class="guide-stat-icon warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="guide-stat-value">{{ $stats['pending_count'] }}</div>
                <p class="guide-stat-label">Chờ xác nhận</p>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="guide-card mb-4">
        <div class="guide-card-body">
            <form method="get" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-filter me-2"></i>Loại
                    </label>
                    <select name="type" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="check_in" {{ request('type') === 'check_in' ? 'selected' : '' }}>Check-in</option>
                        <option value="check_out" {{ request('type') === 'check_out' ? 'selected' : '' }}>Check-out</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-info-circle me-2"></i>Trạng thái
                    </label>
                    <select name="status" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                        <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-calendar me-2"></i>Lịch khởi hành
                    </label>
                    <select name="departure_id" class="form-select">
                        <option value="">Tất cả</option>
                        @foreach($departures as $dep)
                            <option value="{{ $dep->id }}" {{ request('departure_id') == $dep->id ? 'selected' : '' }}>
                                {{ $dep->tour->title }} - {{ $dep->departure_date?->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-calendar-alt me-2"></i>Ngày
                    </label>
                    <input type="date" name="date" value="{{ request('date') }}" class="form-control">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-guide-primary me-2">
                        <i class="fas fa-filter"></i> Lọc
                    </button>
                    <a href="{{ route('guide.check-in-out.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo"></i> Đặt lại
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Check-in/out List -->
    <div class="guide-card">
        <div class="guide-card-header">
            <i class="fas fa-list me-2"></i>
            Danh sách Check-in/Check-out
            <span class="badge bg-primary ms-2">{{ $checkInOuts->total() }}</span>
        </div>
        <div class="guide-card-body">
            @if($checkInOuts->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Chưa có check-in/check-out nào</h5>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table guide-table">
                        <thead>
                            <tr>
                                <th>Thời gian</th>
                                <th>Khách hàng</th>
                                <th>Tour</th>
                                <th>Loại</th>
                                <th>Địa điểm</th>
                                <th>Trạng thái</th>
                                <th>Xác nhận bởi</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($checkInOuts as $checkInOut)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $checkInOut->check_time->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $checkInOut->check_time->format('H:i:s') }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $checkInOut->user->name }}</div>
                                        <small class="text-muted">{{ $checkInOut->user->email }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $checkInOut->booking->tour->title }}</div>
                                        <small class="text-muted">
                                            {{ $checkInOut->booking->departure->departure_date?->format('d/m/Y') }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($checkInOut->type === 'check_in')
                                            <span class="badge bg-success">
                                                <i class="fas fa-sign-in-alt"></i> Check-in
                                            </span>
                                        @else
                                            <span class="badge bg-info">
                                                <i class="fas fa-sign-out-alt"></i> Check-out
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>
                                            <i class="fas fa-map-marker-alt text-primary"></i>
                                            {{ Str::limit($checkInOut->location, 30) }}
                                        </small>
                                    </td>
                                    <td>
                                        @switch($checkInOut->status)
                                            @case('pending')
                                                <span class="badge bg-warning">Chờ xác nhận</span>
                                                @break
                                            @case('confirmed')
                                                <span class="badge bg-success">Đã xác nhận</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-danger">Đã hủy</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($checkInOut->verified_by)
                                            <div class="fw-bold">{{ $checkInOut->verified_by }}</div>
                                            <small class="text-muted">{{ $checkInOut->verified_at?->format('d/m/Y H:i') }}</small>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            @if($checkInOut->status === 'pending')
                                                <form action="{{ route('guide.check-in-out.confirm', $checkInOut) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success" title="Xác nhận">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('guide.check-in-out.cancel', $checkInOut) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger" title="Hủy" onclick="return confirm('Xác nhận hủy?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $checkInOuts->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

