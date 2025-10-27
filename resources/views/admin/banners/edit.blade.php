@extends('layouts.admin')

@section('title', 'Chỉnh sửa Banner')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chỉnh sửa Banner</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title">Tiêu đề *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $banner->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="description">Mô tả</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description', $banner->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="image">Hình ảnh mới</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    <small class="form-text text-muted">Chọn file ảnh mới (JPG, PNG, GIF - tối đa 5MB). Để trống nếu không muốn thay đổi.</small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                    @if($banner->image_url)
                                        <div class="mt-2">
                                            <label>Hình ảnh hiện tại:</label>
                                            <img src="{{ asset($banner->image_url) }}" alt="{{ $banner->title }}" 
                                                 class="img-thumbnail" style="max-width: 200px; max-height: 150px;">
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group mb-3">
                                    <label for="link_url">URL Liên kết</label>
                                    <input type="url" class="form-control @error('link_url') is-invalid @enderror" 
                                           id="link_url" name="link_url" value="{{ old('link_url', $banner->link_url) }}">
                                    @error('link_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="type">Loại Banner *</label>
                                    <select class="form-control @error('type') is-invalid @enderror" 
                                            id="type" name="type" required>
                                        <option value="">Chọn loại banner</option>
                                        <option value="hero" {{ old('type', $banner->type) == 'hero' ? 'selected' : '' }}>Hero Banner</option>
                                        <option value="promotion" {{ old('type', $banner->type) == 'promotion' ? 'selected' : '' }}>Khuyến mãi</option>
                                        <option value="category" {{ old('type', $banner->type) == 'category' ? 'selected' : '' }}>Danh mục</option>
                                        <option value="featured" {{ old('type', $banner->type) == 'featured' ? 'selected' : '' }}>Nổi bật</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="position">Vị trí *</label>
                                    <select class="form-control @error('position') is-invalid @enderror" 
                                            id="position" name="position" required>
                                        <option value="">Chọn vị trí</option>
                                        <option value="top" {{ old('position', $banner->position) == 'top' ? 'selected' : '' }}>Đầu trang</option>
                                        <option value="middle" {{ old('position', $banner->position) == 'middle' ? 'selected' : '' }}>Giữa trang</option>
                                        <option value="bottom" {{ old('position', $banner->position) == 'bottom' ? 'selected' : '' }}>Cuối trang</option>
                                        <option value="sidebar" {{ old('position', $banner->position) == 'sidebar' ? 'selected' : '' }}>Thanh bên</option>
                                    </select>
                                    @error('position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="sort_order">Thứ tự sắp xếp</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', $banner->sort_order) }}" min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                                               value="1" {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Kích hoạt banner
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="start_date">Ngày bắt đầu</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                           id="start_date" name="start_date" value="{{ old('start_date', $banner->start_date ? \Carbon\Carbon::parse($banner->start_date)->format('Y-m-d') : '') }}">
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="end_date">Ngày kết thúc</label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                           id="end_date" name="end_date" value="{{ old('end_date', $banner->end_date ? \Carbon\Carbon::parse($banner->end_date)->format('Y-m-d') : '') }}">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập nhật Banner
                            </button>
                            <a href="{{ route('admin.banners') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
