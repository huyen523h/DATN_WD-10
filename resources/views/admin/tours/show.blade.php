@extends('admin.layout')

@section('content')
<h1>Chi tiết Tour: {{ $tour->title }}</h1>

<div class="row mb-3">
    <div class="col-md-8">
        <p><strong>Danh mục:</strong> {{ optional($tour->category)->name ?? '—' }}</p>
        <p><strong>Thời lượng (text):</strong> {{ $tour->duration ?? '—' }}</p>
        <p><strong>Số ngày/đêm:</strong> {{ $tour->duration_days ?? '—' }} ngày {{ $tour->nights ?? '—' }} đêm</p>
        <p><strong>Trạng thái hiển thị:</strong>
            <span class="badge {{ $tour->status==='active'?'bg-success':($tour->status==='inactive'?'bg-warning':'bg-secondary') }}">{{ $tour->status }}</span>
        </p>
        <p><strong>Tình trạng chỗ (tổng):</strong>
            <span class="badge bg-secondary">{{ $tour->availability_status ?? 'available' }}</span>
        </p>
    </div>
    <div class="col-md-4">
        <p><strong>Giá:</strong> {{ number_format($tour->price,0,',','.') }}đ</p>
        <p><strong>Giá gốc:</strong> {{ $tour->original_price?number_format($tour->original_price,0,',','.').'đ':'—' }}</p>
        <p><strong>Giá khuyến mãi:</strong> {{ $tour->discount_price?number_format($tour->discount_price,0,',','.').'đ':'—' }}</p>
    </div>
</div>

@if($tour->images && $tour->images->count())
<div class="mb-4">
    <h5>Hình ảnh</h5>
    <div class="row">
        @foreach($tour->images as $img)
            <div class="col-md-3 mb-3">
                <img src="{{ $img->image_url }}" class="img-fluid rounded" style="height:150px;object-fit:cover;">
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- Tabs nội dung bổ sung --}}
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab_includes" type="button">Giá bao gồm</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab_excludes" type="button">Giá không bao gồm</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab_surcharges" type="button">Phụ thu</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab_notes" type="button">Lưu ý/Hướng dẫn</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab_cancel" type="button">Hủy/đổi</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab_visa" type="button">Visa</button></li>
</ul>
<div class="tab-content border-start border-end border-bottom p-3 mb-4">
    <div class="tab-pane fade show active" id="tab_includes">{{ $tour->includes ?: '—' }}</div>
    <div class="tab-pane fade" id="tab_excludes">{{ $tour->excludes ?: '—' }}</div>
    <div class="tab-pane fade" id="tab_surcharges">{{ $tour->surcharges ?: '—' }}</div>
    <div class="tab-pane fade" id="tab_notes">{{ $tour->notes ?: '—' }}</div>
    <div class="tab-pane fade" id="tab_cancel">{{ $tour->cancellation_policy ?: '—' }}</div>
    <div class="tab-pane fade" id="tab_visa">{{ $tour->visa_requirements ?: '—' }}</div>
</div>

{{-- Lịch trình --}}
<h5 class="mt-3">Lịch trình chi tiết</h5>
@if($tour->schedules && $tour->schedules->count())
    <ol class="list-group list-group-numbered mb-4">
        @foreach($tour->schedules->sortBy('day_number') as $sc)
            <li class="list-group-item">
                <div class="fw-bold">Ngày {{ $sc->day_number }}: {{ $sc->title }}</div>
                <div class="text-muted">{{ $sc->description }}</div>
            </li>
        @endforeach
    </ol>
@else
    <p class="text-muted">Chưa có lịch trình.</p>
@endif

{{-- Lịch khởi hành theo ngày --}}
<h5>Lịch khởi hành</h5>
@if($tour->departures && $tour->departures->count())
    <div class="table-responsive mb-4">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Ngày khởi hành</th>
                    <th>Tổng chỗ</th>
                    <th>Còn chỗ</th>
                    <th>Giá (ngày)</th>
                    <th>Giá trẻ em</th>
                    <th>Giá trẻ nhỏ</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tour->departures->sortBy('departure_date') as $d)
                    <tr>
                        <td>{{ $d->departure_date?->format('d/m/Y') }}</td>
                        <td>{{ $d->seats_total }}</td>
                        <td>{{ $d->seats_available }}</td>
                        <td>{{ $d->price?number_format($d->price,0,',','.').'đ':'—' }}</td>
                        <td>{{ $d->child_price?number_format($d->child_price,0,',','.').'đ':'—' }}</td>
                        <td>{{ $d->infant_price?number_format($d->infant_price,0,',','.').'đ':'—' }}</td>
                        <td>
                            @php
                                $map = ['available'=>'bg-success','contact'=>'bg-warning','sold_out'=>'bg-danger'];
                                $badge = $map[$d->status] ?? 'bg-secondary';
                            @endphp
                            <span class="badge {{ $badge }}">{{ $d->status }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <p class="text-muted">Chưa có lịch khởi hành.</p>
@endif

{{-- Ngày về (nếu có departure đầu + duration_days) --}}
@if($tour->departure_date && $tour->duration_days)
    <p><strong>Ngày về (từ ngày khởi hành chính):</strong>
        {{ $tour->departure_date->copy()->addDays($tour->duration_days)->format('d/m/Y') }}
    </p>
@endif

<a href="{{ route('admin.tours') }}" class="btn btn-secondary">Quay lại</a>
@endsection
