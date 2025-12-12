@extends('layouts.app')

@section('title', 'Yêu cầu đặc biệt')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('guide.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guide.departures.show', $departure->id) }}">{{ $departure->tour->title }}</a></li>
                    <li class="breadcrumb-item active">Yêu cầu đặc biệt</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-primary">
                        <i class="fas fa-clipboard-list me-2"></i>Yêu cầu đặc biệt
                    </h2>
                    <p class="text-muted">
                        Tour: <strong>{{ $departure->tour->title }}</strong> | 
                        Ngày: <strong>{{ $departure->departure_date->format('d/m/Y') }}</strong>
                    </p>
                </div>
                <a href="{{ route('guide.special-requests.create', $departure->id) }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Thêm yêu cầu
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($requests->count() > 0)
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Khách hàng</th>
                                <th>Loại yêu cầu</th>
                                <th>Tiêu đề</th>
                                <th>Mô tả</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $request)
                                <tr>
                                    <td>
                                        <strong>{{ $request->booking->user->name }}</strong><br>
                                        <small class="text-muted">{{ $request->booking->user->phone }}</small>
                                    </td>
                                    <td>
                                        @if($request->request_type == 'dietary')
                                            <span class="badge bg-info">Ăn uống</span>
                                        @elseif($request->request_type == 'medical')
                                            <span class="badge bg-danger">Y tế</span>
                                        @elseif($request->request_type == 'accessibility')
                                            <span class="badge bg-warning">Tiếp cận</span>
                                        @else
                                            <span class="badge bg-secondary">Khác</span>
                                        @endif
                                    </td>
                                    <td><strong>{{ $request->title }}</strong></td>
                                    <td>{{ Str::limit($request->description, 50) }}</td>
                                    <td>
                                        @if($request->status == 'pending')
                                            <span class="badge bg-warning">Chờ xử lý</span>
                                        @elseif($request->status == 'acknowledged')
                                            <span class="badge bg-info">Đã xác nhận</span>
                                        @elseif($request->status == 'fulfilled')
                                            <span class="badge bg-success">Đã thực hiện</span>
                                        @else
                                            <span class="badge bg-secondary">Đã hủy</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('guide.special-requests.show', [$departure->id, $request->id]) }}" 
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                <p class="text-muted">Chưa có yêu cầu đặc biệt nào.</p>
                <a href="{{ route('guide.special-requests.create', $departure->id) }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Thêm yêu cầu đầu tiên
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

