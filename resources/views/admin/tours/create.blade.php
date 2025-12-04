@extends('layouts.admin')

@section('title', 'Thêm tour mới - Admin')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.tours.index') }}">Quản lý Tours</a></li>
    <li class="breadcrumb-item active">Thêm tour mới</li>
@endsection

@section('content')
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-plus text-primary"></i> Thêm tour mới</h2>
            <p class="text-muted mb-0">Tạo tour du lịch mới cho hệ thống</p>
        </div>
        <a href="{{ route('admin.tours.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <div>
                    <strong>Không thể lưu tour.</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Thông tin cơ bản</h5>
                </div>
                <div class="card-body">

                    <form method="POST" action="{{ route('admin.tours.store') }}" enctype="multipart/form-data"
                        id="tourForm">
                        @csrf

                        <!-- Thông tin cơ bản -->

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Tên tour <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Danh mục <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" id="category_id"
                                        name="category_id" required>
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="duration_days" class="form-label">Số ngày <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('duration_days') is-invalid @enderror"
                                        id="duration_days" name="duration_days" value="{{ old('duration_days') }}"
                                        min="1" max="30" required>
                                    @error('duration_days')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Giá tour (VNĐ) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                        id="price" name="price" value="{{ old('price') }}" min="0"
                                        step="1000" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả tour <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="5" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng thái</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status">
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Hoạt động
                                        </option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tạm
                                            dừng</option>
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Bản nháp
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Giá theo đối tượng -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-users text-primary"></i> Giá theo đối tượng</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user text-primary"></i> Giá người lớn
                        </label>
                        <input type="number" min="0" step="1000" class="form-control" name="price_adult"
                            value="{{ old('price_adult') }}" placeholder="2000000">
                        <div class="form-text">Nếu bỏ trống, giá lịch sẽ fallback về "Giá (VNĐ)".</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-child text-primary"></i> Giá trẻ em
                        </label>
                        <input type="number" min="0" step="1000" class="form-control" name="price_child"
                            value="{{ old('price_child') }}" placeholder="1500000">
                        <div class="form-text">Giá cho trẻ em từ 2-11 tuổi</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-baby text-primary"></i> Giá trẻ nhỏ
                        </label>
                        <input type="number" min="0" step="1000" class="form-control" name="price_infant"
                            value="{{ old('price_infant') }}" placeholder="500000">
                        <div class="form-text">Giá cho trẻ em dưới 2 tuổi</div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Trạng thái và thông tin bổ sung -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle text-primary"></i> Thông tin bổ sung</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-toggle-on text-primary"></i> Trạng thái tour *
                        </label>
                        <select class="form-select" name="status" required>
                            @php $status = old('status','active'); @endphp
                            <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ $status == 'inactive' ? 'selected' : '' }}>Không hoạt động
                            </option>
                            <option value="draft" {{ $status == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                        </select>
                        <div class="form-text">Trạng thái hiển thị của tour.</div>
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-check-circle text-primary"></i> Tình trạng chỗ
                        </label>
                        <select class="form-select" name="availability_status">
                            @php $av = old('availability_status','available'); @endphp
                            <option value="available" {{ $av == 'available' ? 'selected' : '' }}>Còn chỗ</option>
                            <option value="contact" {{ $av == 'contact' ? 'selected' : '' }}>Liên hệ</option>
                            <option value="sold_out" {{ $av == 'sold_out' ? 'selected' : '' }}>Hết chỗ</option>
                        </select>
                        <div class="form-text">Hiển thị trạng thái chung của tour.</div>
                    </div>
                </div>
            </div>


            {{-- <div id="schedule-container">
                            <div class="schedule-item mb-3 p-3 border rounded">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="form-label">Ngày</label>
                                        <input type="number" class="form-control" name="schedule_day[]"
                                               value="1" min="1" max="30">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tiêu đề</label>
                                        <input type="text" class="form-control" name="schedule_title[]"
                                               placeholder="VD: Khởi hành từ Hà Nội">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Mô tả</label>
                                        <textarea class="form-control" name="schedule_description[]"
                                                  rows="2" placeholder="Mô tả chi tiết hoạt động"></textarea>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-outline-danger btn-sm d-block"
                                                onclick="removeSchedule(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div> --}}


            <!-- Chi tiết tour -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list-alt text-primary"></i> Chi tiết tour</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#t_includes"
                                type="button">
                                <i class="fas fa-check-circle"></i> Giá bao gồm
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#t_excludes" type="button">
                                <i class="fas fa-times-circle"></i> Giá không bao gồm
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#t_surcharges" type="button">
                                <i class="fas fa-plus-circle"></i> Phụ thu
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#t_notes" type="button">
                                <i class="fas fa-info-circle"></i> Lưu ý/Hướng dẫn
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#t_cancel" type="button">
                                <i class="fas fa-ban"></i> Hủy/đổi
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#t_visa" type="button">
                                <i class="fas fa-passport"></i> Visa
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content border-start border-end border-bottom p-3">
                        <div class="tab-pane fade show active" id="t_includes">
                            <label class="form-label">Dịch vụ bao gồm trong giá</label>
                            <textarea class="form-control" name="includes" rows="4"
                                placeholder="Ví dụ: Vé máy bay, khách sạn, ăn uống...">{{ old('includes') }}</textarea>
                        </div>
                        <div class="tab-pane fade" id="t_excludes">
                            <label class="form-label">Dịch vụ không bao gồm</label>
                            <textarea class="form-control" name="excludes" rows="4" placeholder="Ví dụ: Chi phí cá nhân, bảo hiểm...">{{ old('excludes') }}</textarea>
                        </div>
                        <div class="tab-pane fade" id="t_surcharges">
                            <label class="form-label">Phụ thu (nếu có)</label>
                            <textarea class="form-control" name="surcharges" rows="4"
                                placeholder="Ví dụ: Phụ thu phòng đơn, phụ thu ngày lễ...">{{ old('surcharges') }}</textarea>
                        </div>
                        <div class="tab-pane fade" id="t_notes">
                            <label class="form-label">Lưu ý và hướng dẫn</label>
                            <textarea class="form-control" name="notes" rows="4"
                                placeholder="Ví dụ: Hướng dẫn chuẩn bị, lưu ý đặc biệt...">{{ old('notes') }}</textarea>
                        </div>
                        <div class="tab-pane fade" id="t_cancel">
                            <label class="form-label">Chính sách hủy/đổi tour</label>
                            <textarea class="form-control" name="cancellation_policy" rows="4"
                                placeholder="Ví dụ: Hủy trước 7 ngày hoàn 100%, hủy trước 3 ngày hoàn 50%...">{{ old('cancellation_policy') }}</textarea>
                        </div>
                        <div class="tab-pane fade" id="t_visa">
                            <label class="form-label">Yêu cầu visa</label>
                            <textarea class="form-control" name="visa_requirements" rows="4"
                                placeholder="Ví dụ: Cần visa, hộ chiếu còn hạn 6 tháng...">{{ old('visa_requirements') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hình ảnh tour -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-images text-primary"></i> Hình ảnh tour</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-upload text-primary"></i> Tải lên hình ảnh
                        </label>
                        <input type="file" class="form-control" name="images[]" multiple accept="image/*">
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i> Có thể chọn nhiều hình ảnh cùng lúc.
                            Hình ảnh đầu tiên sẽ được sử dụng làm ảnh đại diện.
                        </div>
                    </div>
                </div>
            </div>


            <div id="departure-container">
                <div class="departure-item mb-3 p-3 border rounded">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Ngày khởi hành</label>
                            <input type="date" class="form-control" name="departure_date[]"
                                value="{{ date('Y-m-d', strtotime('+7 days')) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tổng số chỗ</label>
                            <input type="number" class="form-control" name="seats_total[]" value="20"
                                min="1" max="100">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Chỗ còn lại</label>
                            <input type="number" class="form-control" name="seats_available[]" value="20"
                                min="0" max="100">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-outline-danger btn-sm d-block"
                                onclick="removeDeparture(this)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-align-left text-primary"></i> Mô tả
                        </label>
                        <textarea class="form-control" name="schedule_description[]" rows="2"
                            placeholder="Mô tả chi tiết hoạt động trong ngày..."></textarea>
                    </div>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-danger btn-sm"
                        onclick="this.closest('.schedule-item').remove()">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Lịch khởi hành -->
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-plane-departure text-primary"></i> Lịch khởi hành</h5>
            <button type="button" class="btn btn-primary btn-sm" onclick="addDeparture()">
                <i class="fas fa-plus"></i> Thêm lịch khởi hành
            </button>
        </div>
        <div class="card-body">
            <div id="departure-container">
                <div class="departure-item mb-3 p-3 shadow-sm rounded-3 bg-white border position-relative">

                    <div class="row g-3">

                        <div class="col-md-2">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-calendar-alt text-primary"></i> Ngày khởi hành
                            </label>
                            <input type="date" class="form-control form-control-sm" name="departure_date[]"
                                value="{{ date('Y-m-d', strtotime('+7 days')) }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-users text-primary"></i> Tổng chỗ
                            </label>
                            <input type="number" class="form-control form-control-sm" name="seats_total[]"
                                value="20" min="1">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user-check text-primary"></i> Còn chỗ
                            </label>
                            <input type="number" class="form-control form-control-sm" name="seats_available[]"
                                value="20" min="0">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-money-bill-wave text-primary"></i> Giá (ngày)
                            </label>
                            <input type="number" class="form-control form-control-sm" name="price_dep[]"
                                placeholder="2000000">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-info-circle text-primary"></i> Trạng thái
                            </label>
                            <select class="form-select form-select-sm" name="status_dep[]">
                                <option value="available">Còn chỗ</option>
                                <option value="contact">Liên hệ</option>
                                <option value="sold_out">Hết chỗ</option>
                            </select>
                        </div>

                        <div class="col-md-1">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-child text-primary"></i> Trẻ em
                            </label>
                            <input type="number" class="form-control form-control-sm" name="child_price[]"
                                placeholder="1500000">
                        </div>

                        <div class="col-md-1">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-baby text-primary"></i> Trẻ nhỏ
                            </label>
                            <input type="number" class="form-control form-control-sm" name="infant_price[]"
                                placeholder="500000">
                        </div>

                        <div class="col-12 text-end pt-2">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-btn">
                                <i class="fas fa-trash"></i> Xóa lịch
                            </button>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Submit Actions -->
    <div class="card mt-4 submit-section">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">
                        <i class="fas fa-check-circle text-primary"></i> Hoàn tất tạo tour
                    </h6>
                    <p class="text-muted mb-0">Kiểm tra lại thông tin trước khi lưu</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.tours.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                    <button type="submit" class="btn btn-primary" form="tourForm" id="saveBtn">
                        <i class="fas fa-save"></i> Lưu tour
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        /* Enhanced Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
            animation: slideInUp 0.5s ease;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-label {
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition);
        }

        .form-label i {
            font-size: 1rem;
            opacity: 0.7;
            transition: var(--transition);
        }

        .form-group:hover .form-label i {
            opacity: 1;
            transform: scale(1.1);
        }

        .form-control {
            transition: var(--transition);
            position: relative;
        }

        .form-control:focus {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(14, 165, 233, 0.15);
        }

        .form-text {
            font-size: 0.75rem;
            color: var(--gray-500);
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            animation: fadeIn 0.3s ease;
        }

        /* Enhanced Card Animations */
        .card {
            animation: cardSlideIn 0.6s ease;
            position: relative;
            overflow: hidden;
        }

        @keyframes cardSlideIn {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-500), var(--primary-600), var(--primary-500));
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
            opacity: 0;
            transition: var(--transition);
        }

        .card:hover::before {
            opacity: 1;
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }

        .card-header {
            background: linear-gradient(135deg, var(--gray-50), white);
            position: relative;
            overflow: hidden;
        }

        .card-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 1.5rem;
            right: 1.5rem;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--primary-300), transparent);
        }

        .card-header h5 {
            color: var(--gray-800);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
        }

        .card-header h5 i {
            color: var(--primary-600);
            animation: iconPulse 2s ease-in-out infinite;
        }

        @keyframes iconPulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        /* Enhanced Schedule and Departure Items */
        .schedule-item,
        .departure-item {
            transition: var(--transition);
            border: 2px solid var(--gray-200) !important;
            border-radius: var(--radius-lg);
            position: relative;
            overflow: hidden;
            animation: itemSlideIn 0.4s ease;
        }

        @keyframes itemSlideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .schedule-item::before,
        .departure-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, var(--primary-500), var(--primary-600));
            transform: scaleY(0);
            transition: var(--transition);
        }

        .schedule-item:hover::before,
        .departure-item:hover::before {
            transform: scaleY(1);
        }

        .schedule-item:hover,
        .departure-item:hover {
            border-color: var(--primary-300) !important;
            box-shadow: 0 8px 25px rgba(14, 165, 233, 0.15);
            transform: translateY(-2px);
        }

        /* Enhanced Tabs */
        .nav-tabs {
            border-bottom: 2px solid var(--gray-200);
            position: relative;
        }

        .nav-tabs::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--primary-500), var(--primary-600));
            opacity: 0.3;
        }

        .nav-tabs .nav-link {
            border-radius: var(--radius) var(--radius) 0 0;
            border: 2px solid transparent;
            color: var(--gray-600);
            font-weight: 500;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            margin-right: 0.5rem;
        }

        .nav-tabs .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(14, 165, 233, 0.1), transparent);
            transition: left 0.5s;
        }

        .nav-tabs .nav-link:hover::before {
            left: 100%;
        }

        .nav-tabs .nav-link:hover {
            border-color: var(--gray-300);
            color: var(--primary-600);
            transform: translateY(-2px);
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-600);
            background: white;
            border-color: var(--primary-200) var(--primary-200) white;
            box-shadow: 0 -4px 12px rgba(14, 165, 233, 0.1);
            transform: translateY(-2px);
        }

        .nav-tabs .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--primary-500);
        }

        .tab-content {
            border: 2px solid var(--gray-200);
            border-top: none;
            border-radius: 0 0 var(--radius-lg) var(--radius-lg);
            background: white;
            position: relative;
        }

        .tab-pane {
            animation: tabFadeIn 0.3s ease;
        }

        @keyframes tabFadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Enhanced Buttons */
        .btn {
            position: relative;
            overflow: hidden;
            transition: var(--transition);
        }

        .btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:active::after {
            width: 300px;
            height: 300px;
        }

        .btn-group .btn {
            margin: 0 2px;
            transition: var(--transition);
        }

        .btn-group .btn:hover {
            transform: translateY(-2px);
            z-index: 10;
        }

        /* Enhanced File Upload */
        .form-control[type="file"] {
            position: relative;
            cursor: pointer;
            transition: var(--transition);
        }

        .form-control[type="file"]:hover {
            border-color: var(--primary-500);
            background: var(--primary-50);
        }

        .form-control[type="file"]::before {
            content: '📁 Chọn file';
            position: absolute;
            top: 50%;
            left: 1rem;
            transform: translateY(-50%);
            color: var(--gray-500);
            pointer-events: none;
        }

        /* Enhanced Submit Section */
        .submit-section {
            background: linear-gradient(135deg, var(--primary-50), white);
            border: 2px solid var(--primary-200);
            border-radius: var(--radius-lg);
            position: relative;
            overflow: hidden;
        }

        .submit-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-500), var(--primary-600));
            animation: progressBar 2s ease-in-out infinite;
        }

        @keyframes progressBar {

            0%,
            100% {
                transform: translateX(-100%);
            }

            50% {
                transform: translateX(100%);
            }
        }

        /* Loading States */
        .loading {
            position: relative;
            pointer-events: none;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius);
        }

        /* Success Animation */
        .success-animation {
            animation: successPulse 0.6s ease;
        }

        @keyframes successPulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Error Animation */
        .error-animation {
            animation: errorShake 0.6s ease;
        }

        @keyframes errorShake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        /* Responsive Enhancements */
        @media (max-width: 768px) {
            .card {
                margin-bottom: 1rem;
            }

            .nav-tabs .nav-link {
                font-size: 0.75rem;
                padding: 0.5rem 0.75rem;
            }

            .form-group {
                margin-bottom: 1rem;
            }
        }

        /* Dark mode enhancements */
        @media (prefers-color-scheme: dark) {
            .card {
                background: var(--gray-800);
                border-color: var(--gray-700);
            }

            .form-control {
                background: var(--gray-700);
                border-color: var(--gray-600);
                color: var(--gray-100);
            }

            .nav-tabs .nav-link {
                color: var(--gray-300);
            }

            .nav-tabs .nav-link.active {
                background: var(--gray-800);
                color: var(--primary-400);
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        // Enhanced animations and interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Add smooth scrolling to form sections
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });

            // Enhanced form field interactions
            const formControls = document.querySelectorAll('.form-control, .form-select');
            formControls.forEach(control => {
                control.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });

                control.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                });
            });

            // Auto-save functionality (optional)
            let autoSaveTimeout;
            const form = document.getElementById('tourForm');
            if (form) {
                form.addEventListener('input', function() {
                    clearTimeout(autoSaveTimeout);
                    autoSaveTimeout = setTimeout(() => {
                        // Auto-save logic here
                        console.log('Auto-saving...');
                    }, 2000);
                });
            }
        });

        function addSchedule() {
            const c = document.getElementById('schedule-container');
            const i = c.children.length + 1;

            // Create new schedule item with animation
            const newItem = document.createElement('div');
            newItem.className = 'schedule-item mb-3 p-3 border rounded bg-light';
            newItem.style.opacity = '0';
            newItem.style.transform = 'translateX(-20px)';

            newItem.innerHTML = `

        <div class="row">
            <div class="col-md-2">
                <label class="form-label">Ngày</label>
                <input type="number" class="form-control" name="schedule_day[]"
                       value="${container.children.length + 1}" min="1" max="30">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tiêu đề</label>
                <input type="text" class="form-control" name="schedule_title[]"
                       placeholder="VD: Khởi hành từ Hà Nội">
            </div>
            <div class="col-md-5">
                <label class="form-label">Mô tả</label>
                <textarea class="form-control" name="schedule_description[]"
                          rows="2" placeholder="Mô tả chi tiết hoạt động"></textarea>
            </div>
            <div class="col-md-1">
                <label class="form-label">&nbsp;</label>
                <button type="button" class="btn btn-outline-danger btn-sm d-block"
                        onclick="removeSchedule(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        <div class="col-md-1 d-flex align-items-end">
          <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeScheduleItem(this)">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </div>
    `;

            c.appendChild(newItem);

            // Animate in
            setTimeout(() => {
                newItem.style.transition = 'all 0.4s ease';
                newItem.style.opacity = '1';
                newItem.style.transform = 'translateX(0)';
            }, 10);

            // Add success feedback
            showNotification('Đã thêm ngày mới vào lịch trình', 'success');
        }

        function addDeparture() {
            const c = document.getElementById('departure-container');

            // Create new departure item with animation
            const newItem = document.createElement('div');
            newItem.className = 'departure-item mb-3 p-3 border rounded bg-light';
            newItem.style.opacity = '0';
            newItem.style.transform = 'translateX(-20px)';

            newItem.innerHTML = `

        <div class="row">
            <div class="col-md-4">
                <label class="form-label">Ngày khởi hành</label>
                <input type="date" class="form-control" name="departure_date[]"
                       value="{{ date('Y-m-d', strtotime('+7 days')) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tổng số chỗ</label>
                <input type="number" class="form-control" name="seats_total[]"
                       value="20" min="1" max="100">
            </div>
            <div class="col-md-3">
                <label class="form-label">Chỗ còn lại</label>
                <input type="number" class="form-control" name="seats_available[]"
                       value="20" min="0" max="100">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="button" class="btn btn-outline-danger btn-sm d-block"
                        onclick="removeDeparture(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-user-check text-primary"></i> Còn chỗ
            </label>
            <input type="number" class="form-control" name="seats_available[]" value="20" min="0" max="100">
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-money-bill-wave text-primary"></i> Giá (ngày)
            </label>
            <input type="number" class="form-control" name="price_dep[]" step="1000" min="0" placeholder="2000000">
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-info-circle text-primary"></i> Trạng thái
            </label>
            <select class="form-select" name="status_dep[]">
                <option value="available">Còn chỗ</option>
                <option value="contact">Liên hệ</option>
                <option value="sold_out">Hết chỗ</option>
            </select>
          </div>
        </div>
        <div class="col-md-1">
          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-child text-primary"></i> Giá trẻ em
            </label>
            <input type="number" class="form-control" name="child_price[]" step="1000" min="0" placeholder="1500000">
          </div>
        </div>
        <div class="col-md-1">
          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-baby text-primary"></i> Giá trẻ nhỏ
            </label>
            <input type="number" class="form-control" name="infant_price[]" step="1000" min="0" placeholder="500000">
          </div>
        </div>
        <div class="col-md-12 d-flex justify-content-end">
          <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeDepartureItem(this)">
            <i class="fas fa-trash"></i> Xóa lịch
          </button>
        </div>
      </div>
    `;

            c.appendChild(newItem);

            // Animate in
            setTimeout(() => {
                newItem.style.transition = 'all 0.4s ease';
                newItem.style.opacity = '1';
                newItem.style.transform = 'translateX(0)';
            }, 10);

            // Add success feedback
            showNotification('Đã thêm lịch khởi hành mới', 'success');
        }

        function removeScheduleItem(button) {
            const item = button.closest('.schedule-item');
            item.style.transition = 'all 0.3s ease';
            item.style.opacity = '0';
            item.style.transform = 'translateX(-20px)';

            setTimeout(() => {
                item.remove();
                showNotification('Đã xóa ngày khỏi lịch trình', 'info');
            }, 300);
        }

        function removeDepartureItem(button) {
            const item = button.closest('.departure-item');
            item.style.transition = 'all 0.3s ease';
            item.style.opacity = '0';
            item.style.transform = 'translateX(-20px)';

            setTimeout(() => {
                item.remove();
                showNotification('Đã xóa lịch khởi hành', 'info');
            }, 300);
        }

        // Enhanced form validation with animations
        document.getElementById('tourForm').addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            let firstInvalidField = null;

            // Reset previous validation states
            this.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
                field.classList.remove('error-animation');
            });

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    field.classList.add('error-animation');
                    isValid = false;

                    if (!firstInvalidField) {
                        firstInvalidField = field;
                    }
                } else {
                    field.classList.remove('is-invalid');
                    field.classList.add('success-animation');
                    setTimeout(() => {
                        field.classList.remove('success-animation');
                    }, 600);
                }
            });

            if (!isValid) {
                e.preventDefault();

                // Scroll to first invalid field
                if (firstInvalidField) {
                    firstInvalidField.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    firstInvalidField.focus();
                }

                showNotification('Vui lòng điền đầy đủ các trường bắt buộc!', 'error');
                return false;
            }

            // Show loading state
            const saveBtn = document.getElementById('saveBtn');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang lưu...';
            saveBtn.disabled = true;
            saveBtn.classList.add('loading');

            // Simulate loading (remove this in production)
            setTimeout(() => {
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
                saveBtn.classList.remove('loading');
                showNotification('Tour đã được lưu thành công!', 'success');
            }, 2000);
        });

        // Notification system
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show`;
            notification.style.position = 'fixed';
            notification.style.top = '20px';
            notification.style.right = '20px';
            notification.style.zIndex = '9999';
            notification.style.minWidth = '300px';
            notification.style.animation = 'slideInRight 0.3s ease';

            const icon = type === 'success' ? 'check-circle' :
                type === 'error' ? 'exclamation-triangle' :
                type === 'warning' ? 'exclamation-circle' : 'info-circle';

            notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${icon} me-2"></i>
            <span>${message}</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

            document.body.appendChild(notification);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.style.animation = 'slideOutRight 0.3s ease';
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }
            }, 5000);
        }

        // Add CSS for notification animations
        const style = document.createElement('style');
        style.textContent = `
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100%);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideOutRight {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(100%);
        }
    }
    
    .is-invalid {
        border-color: var(--danger-500) !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
    }
    
    .focused .form-label {
        color: var(--primary-600);
        transform: scale(1.02);
    }
`;
        document.head.appendChild(style);

        // Enhanced tab switching
        document.querySelectorAll('.nav-link[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('click', function() {
                // Add animation to tab content
                const target = document.querySelector(this.getAttribute('data-bs-target'));
                if (target) {
                    target.style.opacity = '0';
                    target.style.transform = 'translateY(10px)';

                    setTimeout(() => {
                        target.style.transition = 'all 0.3s ease';
                        target.style.opacity = '1';
                        target.style.transform = 'translateY(0)';
                    }, 50);
                }
            });
        });

        // Price calculation helper
        function calculateTotalPrice() {
            const price = parseFloat(document.querySelector('input[name="price"]')?.value || 0);
            const discountPrice = parseFloat(document.querySelector('input[name="discount_price"]')?.value || 0);

            if (discountPrice > 0 && discountPrice < price) {
                const savings = price - discountPrice;
                const savingsPercent = Math.round((savings / price) * 100);

                showNotification(`Khách hàng sẽ tiết kiệm ${savingsPercent}% (${savings.toLocaleString()}đ)`, 'success');
            }
        }

        // Add event listeners for price fields
        document.querySelectorAll('input[name="price"], input[name="discount_price"]').forEach(field => {
            field.addEventListener('input', calculateTotalPrice);
        });
    </script>
@endsection
