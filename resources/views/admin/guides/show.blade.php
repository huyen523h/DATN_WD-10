@extends('layouts.admin')

@section('title', 'Chi tiết hướng dẫn viên')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.guides.index') }}" class="text-decoration-none text-muted">
                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
            </a>
            <h1 class="h3 mt-2">{{ $guide->full_name }}</h1>
            <p class="text-muted mb-0">Mã HDV: {{ $guide->code }}</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.guides.edit', $guide) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i> Chỉnh sửa
            </a>
            <form action="{{ route('admin.guides.destroy', $guide) }}" method="POST"
                onsubmit="return confirm('Xác nhận xoá hướng dẫn viên này?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">Thông tin cá nhân</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>Ngày sinh:</strong>
                            <div>{{ optional($guide->date_of_birth)->format('d/m/Y') ?? 'Chưa cập nhật' }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Giới tính:</strong>
                            <div>{{ $guide->gender ?? 'Chưa cập nhật' }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Liên hệ:</strong>
                            <div>{{ $guide->phone }} / {{ $guide->email }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Địa chỉ:</strong>
                            <div>{{ $guide->address ?? 'Chưa cập nhật' }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Ngôn ngữ chính:</strong>
                            <div>{{ $guide->primary_language ?? 'Chưa cập nhật' }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Liên hệ khẩn cấp:</strong>
                            <div>{{ $guide->emergency_contact_name }} - {{ $guide->emergency_contact_phone }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Tuyến chuyên môn:</strong>
                            <div>{{ $guide->specialty_routes ?? 'Chưa cập nhật' }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Tình trạng sức khoẻ:</strong>
                            <div>{{ $guide->health_status ?? 'Chưa cập nhật' }}</div>
                        </div>
                    </div>
                    <hr>
                    <p class="mb-0"><strong>Tiểu sử:</strong></p>
                    <p class="text-muted">{{ $guide->biography ?? 'Chưa cập nhật' }}</p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Ngôn ngữ sử dụng</span>
                    <span class="badge bg-info">{{ $guide->languages->count() }} ngôn ngữ</span>
                </div>
                <div class="card-body">
                    @forelse ($guide->languages as $language)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $language->language }}</strong>
                                    <div class="text-muted">
                                        Trình độ: {{ ucfirst($language->proficiency) }}
                                    </div>
                                </div>
                                <div>
                                    <div>Mã C/C: <strong>{{ $language->certification_code ?? 'N/A' }}</strong></div>
                                    <small class="text-muted">Ngày cấp: {{ optional($language->certified_at)->format('d/m/Y') }}</small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted">Chưa khai báo ngôn ngữ.</div>
                    @endforelse
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">Hồ sơ sức khoẻ</div>
                <div class="card-body">
                    @forelse ($guide->healthRecords as $record)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ optional($record->check_date)->format('d/m/Y') }}</strong>
                                    <div class="text-muted">{{ $record->hospital }}</div>
                                </div>
                                <span class="badge bg-success">{{ $record->status }}</span>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">Bác sĩ: {{ $record->doctor_name }}</small>
                                <p class="mb-0">{{ $record->notes }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted">Chưa có lịch sử sức khoẻ.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    @if ($guide->avatar_url)
                        <img src="{{ $guide->avatar_url }}" alt="{{ $guide->full_name }}" class="rounded-circle mb-3" width="120"
                            height="120">
                    @else
                        <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                            style="width: 120px; height: 120px;">
                            <span style="font-size: 48px;">{{ strtoupper(substr($guide->full_name, 0, 1)) }}</span>
                        </div>
                    @endif
                    <h5 class="mb-0">{{ $guide->full_name }}</h5>
                    <p class="text-muted">{{ $guide->primary_language }}</p>
                    <div class="d-flex justify-content-center gap-3">
                        <div>
                            <div class="h4 mb-0">{{ $guide->experience_years ?? 0 }}</div>
                            <small class="text-muted">Năm kinh nghiệm</small>
                        </div>
                        <div>
                            <div class="h4 mb-0">{{ number_format($guide->rating_average, 1) }}</div>
                            <small class="text-muted">{{ $guide->rating_count }} lượt đánh giá</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">Nhóm phụ trách</div>
                <div class="card-body">
                    @forelse ($guide->categories as $category)
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>{{ $category->name }}</span>
                            <small class="text-muted">{{ $category->type }}</small>
                        </div>
                    @empty
                        <div class="text-muted">Chưa phân nhóm</div>
                    @endforelse
                </div>
            </div>

            <div class="card">
                <div class="card-header">Tài liệu & Chứng chỉ</div>
                <div class="card-body">
                    @forelse ($guide->documents as $document)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $document->name }}</strong>
                                <span class="badge bg-light text-dark">{{ strtoupper($document->status) }}</span>
                            </div>
                            <small class="text-muted d-block">{{ $document->type }} - {{ $document->issued_by }}</small>
                            <small class="text-muted">
                                {{ optional($document->issued_at)->format('d/m/Y') }} → {{ optional($document->expires_at)->format('d/m/Y') }}
                            </small>
                            <p class="mb-0">{{ $document->notes }}</p>
                        </div>
                    @empty
                        <div class="text-muted">Chưa có tài liệu.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

