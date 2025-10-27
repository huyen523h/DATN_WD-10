@extends('layouts.admin')

@section('title', 'Thêm Banner Mới')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thêm Banner Mới</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title">Tiêu đề *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="description">Mô tả</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="image">Hình ảnh *</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*" required>
                                    <small class="form-text text-muted">Chọn file ảnh (JPG, PNG, GIF - tối đa 5MB)</small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="link_url">URL Liên kết</label>
                                    <input type="url" class="form-control @error('link_url') is-invalid @enderror" 
                                           id="link_url" name="link_url" value="{{ old('link_url') }}">
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
                                        <option value="hero" {{ old('type') == 'hero' ? 'selected' : '' }}>Hero Banner</option>
                                        <option value="promotion" {{ old('type') == 'promotion' ? 'selected' : '' }}>Khuyến mãi</option>
                                        <option value="category" {{ old('type') == 'category' ? 'selected' : '' }}>Danh mục</option>
                                        <option value="featured" {{ old('type') == 'featured' ? 'selected' : '' }}>Nổi bật</option>
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
                                        <option value="top" {{ old('position') == 'top' ? 'selected' : '' }}>Đầu trang</option>
                                        <option value="middle" {{ old('position') == 'middle' ? 'selected' : '' }}>Giữa trang</option>
                                        <option value="bottom" {{ old('position') == 'bottom' ? 'selected' : '' }}>Cuối trang</option>
                                        <option value="sidebar" {{ old('position') == 'sidebar' ? 'selected' : '' }}>Thanh bên</option>
                                    </select>
                                    @error('position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="sort_order">Thứ tự sắp xếp</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                                               value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Kích hoạt banner
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="start_date">Ngày bắt đầu</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                           id="start_date" name="start_date" value="{{ old('start_date') }}">
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="end_date">Ngày kết thúc</label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                           id="end_date" name="end_date" value="{{ old('end_date') }}">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Lưu Banner
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
