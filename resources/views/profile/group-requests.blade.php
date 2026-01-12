@extends('layouts.app')

@section('title', 'Lịch sử yêu cầu Tour đoàn')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Tài khoản</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('profile.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-user me-2"></i> Thông tin cá nhân
                    </a>
                    <a href="{{ route('bookings.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-calendar-check me-2"></i> Đặt tour của tôi
                    </a>
                    <a href="{{ route('profile.group-requests') }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-users me-2"></i> Yêu cầu tour đoàn
                    </a>
                    {{-- Các menu khác nếu có --}}
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 text-primary"><i class="fas fa-history"></i> Lịch sử yêu cầu Tour đoàn</h5>
                    <a href="{{ route('group-tour.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Tạo yêu cầu mới
                    </a>
                </div>
                <div class="card-body">
                    @if($requests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Mã YC</th>
                                        <th>Điểm đến</th>
                                        <th>Ngày đi</th>
                                        <th>Số lượng</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày gửi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $req)
                                        <tr>
                                            <td><strong>#{{ $req->id }}</strong></td>
                                            <td>
                                                {{ $req->destination }}<br>
                                                <small class="text-muted">{{ $req->duration }}</small>
                                            </td>
                                            <td>{{ $req->departure_date->format('d/m/Y') }}</td>
                                            <td>
                                                {{ $req->adults + $req->children }} khách
                                            </td>
                                            <td>
                                                @if($req->status == 'pending')
                                                    <span class="badge bg-warning text-dark">Đang chờ</span>
                                                @elseif($req->status == 'contacted')
                                                    <span class="badge bg-info text-dark">Đang tư vấn</span>
                                                @elseif($req->status == 'contracted')
                                                    <span class="badge bg-success">Đã chốt</span>
                                                @elseif($req->status == 'cancelled')
                                                    <span class="badge bg-danger">Đã hủy</span>
                                                @endif
                                            </td>
                                            <td>{{ $req->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $requests->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <img src="{{ placeholder_url('150','6B7280','ffffff','No Request') }}" alt="Empty" class="mb-3 opacity-50" style="width: 100px;">
                            <p class="text-muted">Bạn chưa gửi yêu cầu đặt tour đoàn nào.</p>
                            <a href="{{ route('group-tour.create') }}" class="btn btn-outline-primary">
                                Gửi yêu cầu ngay
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection