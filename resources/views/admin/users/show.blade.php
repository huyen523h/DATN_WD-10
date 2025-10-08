@extends('layouts.admin')

@section('title', 'Chi tiết Người dùng')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chi tiết Người dùng: {{ $user->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Sửa
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">ID:</th>
                                    <td>{{ $user->id }}</td>
                                </tr>
                                <tr>
                                    <th>Tên:</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Số điện thoại:</th>
                                    <td>{{ $user->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Địa chỉ:</th>
                                    <td>{{ $user->address ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Vai trò:</th>
                                    <td>
                                        @foreach($user->roles as $role)
                                            <span class="badge badge-{{ $role->name == 'admin' ? 'danger' : ($role->name == 'staff' ? 'warning' : 'info') }}">
                                                {{ ucfirst($role->name) }}
                                            </span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>Ngày tạo:</th>
                                    <td>{{ $user->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Cập nhật cuối:</th>
                                    <td>{{ $user->updated_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Số booking:</th>
                                    <td>{{ $user->bookings->count() }}</td>
                                </tr>
                                <tr>
                                    <th>Số đánh giá:</th>
                                    <td>{{ $user->reviews->count() }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($user->bookings->count() > 0)
                        <div class="mt-4">
                            <h5>Danh sách Booking</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tour</th>
                                            <th>Ngày đặt</th>
                                            <th>Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user->bookings as $booking)
                                            <tr>
                                                <td>{{ $booking->id }}</td>
                                                <td>{{ $booking->tour->title ?? 'N/A' }}</td>
                                                <td>{{ $booking->created_at->format('d/m/Y') }}</td>
                                                <td>
                                                    <span class="badge badge-info">{{ $booking->status ?? 'N/A' }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if($user->reviews->count() > 0)
                        <div class="mt-4">
                            <h5>Danh sách Đánh giá</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tour</th>
                                            <th>Điểm</th>
                                            <th>Bình luận</th>
                                            <th>Ngày</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user->reviews as $review)
                                            <tr>
                                                <td>{{ $review->id }}</td>
                                                <td>{{ $review->tour->title ?? 'N/A' }}</td>
                                                <td>
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                    @endfor
                                                </td>
                                                <td>{{ Str::limit($review->comment, 50) }}</td>
                                                <td>{{ $review->created_at->format('d/m/Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
