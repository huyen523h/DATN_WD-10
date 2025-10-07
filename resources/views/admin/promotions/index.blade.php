@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-percentage"></i> Quản lý Mã giảm giá</h2>
                <a href="{{ route('admin.promotions.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tạo mã giảm giá
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

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list"></i> Danh sách mã giảm giá
                    </h6>
                </div>
                <div class="card-body">
                    @if($promotions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Mã</th>
                                        <th>Mô tả</th>
                                        <th>Giảm (%)</th>
                                        <th>Giảm (VND)</th>
                                        <th>Bắt đầu</th>
                                        <th>Kết thúc</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($promotions as $promotion)
                                    <tr>
                                        <td><strong class="text-primary">{{ $promotion->code }}</strong></td>
                                        <td>{{ $promotion->description }}</td>
                                        <td>
                                            @if($promotion->discount_percent)
                                                <span class="badge bg-info">{{ $promotion->discount_percent }}%</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($promotion->discount_amount)
                                                <span class="text-success">{{ number_format($promotion->discount_amount, 0) }}đ</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $promotion->start_date->format('d/m/Y') }}</td>
                                        <td>{{ $promotion->end_date->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($promotion->status === 'active') bg-success
                                                @else bg-secondary
                                                @endif">
                                                {{ $promotion->status === 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.promotions.edit', $promotion) }}" 
                                                   class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.promotions.destroy', $promotion) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger" 
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa mã giảm giá này?')" 
                                                            title="Xóa">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $promotions->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-percentage fa-4x text-muted mb-4"></i>
                            <h4>Chưa có mã giảm giá nào</h4>
                            <p class="text-muted">Hãy tạo mã giảm giá đầu tiên của bạn</p>
                            <a href="{{ route('admin.promotions.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tạo mã giảm giá
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


