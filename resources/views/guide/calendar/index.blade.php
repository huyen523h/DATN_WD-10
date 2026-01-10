@extends('layouts.app')

@section('title', 'Lịch làm việc')

@section('content')
<div class="container py-5">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">
            <i class="fas fa-calendar-alt me-2"></i>Lịch làm việc
        </h2>

        <a href="{{ route('guide.dashboard') }}"
           class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại Dashboard
        </a>
    </div>

    <!-- TẠM THỜI HIỂN THỊ DẠNG LIST -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Các tour đã được phân công
            </h5>
        </div>

        <div class="card-body">
            @if($departures->count())
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Tour</th>
                            <th>Ngày khởi hành</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($departures as $departure)
                            <tr>
                                <td>
                                    <strong>{{ $departure->tour->title }}</strong>
                                </td>
                                <td>
                                    {{ $departure->departure_date->format('d/m/Y') }}
                                </td>
                                <td>
                                    @if($departure->departure_date >= now())
                                        <span class="badge bg-success">Sắp tới</span>
                                    @else
                                        <span class="badge bg-secondary">Đã qua</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('guide.departures.show', $departure->id) }}"
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Chi tiết
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted text-center py-4">
                    Bạn chưa được phân công tour nào
                </p>
            @endif
        </div>
    </div>

</div>
@endsection
