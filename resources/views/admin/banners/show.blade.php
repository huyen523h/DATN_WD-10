@extends('layouts.admin')

@section('title', 'Chi tiết Banner')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Chi tiết Banner</h3>
                    <div>
                        <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Chỉnh sửa
                        </a>
                        <a href="{{ route('admin.banners') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Tiêu đề:</label>
                                <p>{{ $banner->title }}</p>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Mô tả:</label>
                                <p>{{ $banner->description ?: 'Không có mô tả' }}</p>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Loại Banner:</label>
                                <span class="badge badge-{{ $banner->type == 'hero' ? 'primary' : ($banner->type == 'promotion' ? 'success' : 'info') }}">
                                    {{ ucfirst($banner->type) }}
                                </span>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Vị trí:</label>
                                <p>{{ ucfirst($banner->position) }}</p>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Trạng thái:</label>
                                <span class="badge badge-{{ $banner->is_active ? 'success' : 'danger' }}">
                                    {{ $banner->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Hình ảnh:</label>
                                <div class="text-center">
                                    @if($banner->image_url)
                                        <img src="{{ asset($banner->image_url) }}" alt="{{ $banner->title }}" 
                                             class="img-fluid rounded" style="max-height: 300px;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                             style="height: 200px;">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">URL Liên kết:</label>
                                <p>
                                    @if($banner->link_url)
                                        <a href="{{ $banner->link_url }}" target="_blank" class="text-primary">
                                            {{ $banner->link_url }}
                                        </a>
                                    @else
                                        Không có liên kết
                                    @endif
                                </p>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Thứ tự sắp xếp:</label>
                                <p>{{ $banner->sort_order }}</p>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Ngày bắt đầu:</label>
                                <p>{{ $banner->start_date ? \Carbon\Carbon::parse($banner->start_date)->format('d/m/Y') : 'Không giới hạn' }}</p>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Ngày kết thúc:</label>
                                <p>{{ $banner->end_date ? \Carbon\Carbon::parse($banner->end_date)->format('d/m/Y') : 'Không giới hạn' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Thống kê</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center">
                                            <h4>{{ $banner->view_count }}</h4>
                                            <p class="mb-0">Lượt xem</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center">
                                            <h4>{{ $banner->click_count }}</h4>
                                            <p class="mb-0">Lượt click</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-info text-white">
                                        <div class="card-body text-center">
                                            <h4>{{ $banner->view_count > 0 ? round(($banner->click_count / $banner->view_count) * 100, 2) : 0 }}%</h4>
                                            <p class="mb-0">Tỷ lệ click</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-warning text-white">
                                        <div class="card-body text-center">
                                            <h4>{{ $banner->created_at ? \Carbon\Carbon::parse($banner->created_at)->format('d/m/Y') : 'N/A' }}</h4>
                                            <p class="mb-0">Ngày tạo</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
