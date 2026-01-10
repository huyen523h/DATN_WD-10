@extends('layouts.app')

@section('title', 'Điểm danh đoàn')

@section('content')
<div class="container py-4">

    {{-- HEADER --}}
    <h4 class="fw-bold text-primary mb-1">
        <i class="fas fa-users me-2"></i>Điểm danh đoàn
    </h4>
    <div class="text-muted mb-3">
        Tour: <strong>{{ $departure->tour->title }}</strong> |
        Ngày: <strong>{{ $departure->departure_date?->format('d/m/Y') }}</strong>
    </div>

    {{-- CARD --}}
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between">
            <span>
                <i class="fas fa-clipboard-check me-2"></i>Danh sách đoàn
            </span>
            <small>Tổng số hành khách: {{ $passengers->count() }}</small>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="40">#</th>
                        <th>Họ tên</th>
                        <th>Loại</th>
                        <th>Năm sinh</th>
                        <th>Thuộc booking</th>
                        <th>Liên hệ</th>
                        <th class="text-center" width="120">Trạng thái</th>
                        <th class="text-center" width="220">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($passengers as $i => $p)
                        @php
                            $checkIn = $p->checkIns->first();
                        @endphp

                        {{-- MÀU DÒNG --}}
                        <tr
                            class="
                                @if(!$checkIn) table-secondary
                                @elseif($checkIn->status === 'checked_in') table-success
                                @elseif($checkIn->status === 'absent') table-danger
                                @endif
                            "
                        >
                            {{-- STT --}}
                            <td>{{ $i + 1 }}</td>

                            {{-- HỌ TÊN --}}
                            <td class="fw-semibold">
                                {{ $p->full_name }}
                                <div class="small text-muted">
                                    CCCD: {{ $p->id_number ?? '—' }}
                                </div>
                            </td>

                            {{-- LOẠI --}}
                            <td>
                                <span class="badge bg-info">
                                    {{ $p->passenger_type === 'adult' ? 'Người lớn' : 'Trẻ em' }}
                                </span>
                            </td>

                            {{-- NĂM SINH --}}
                            <td>{{ $p->birth_year ?? '—' }}</td>

                            {{-- BOOKING --}}
                            <td>
                                Booking #{{ $p->booking->id }}
                                <div class="small text-muted">
                                    {{ $p->booking->user->name ?? '' }}
                                </div>
                            </td>

                            {{-- LIÊN HỆ --}}
                            <td>
                                <i class="fas fa-phone me-1"></i>
                                {{ $p->booking->user->phone ?? '—' }}
                            </td>

                            {{-- TRẠNG THÁI (HIỂN THỊ RÕ KHÁCH ĐÃ CHECK-IN) --}}
                            <td class="text-center">
                                @if(!$checkIn)
                                    <span class="badge bg-secondary">
                                        Chưa điểm danh
                                    </span>
                                @elseif($checkIn->status === 'checked_in')
                                    <span class="badge bg-success">
                                        ✔ Đã check-in
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        ✖ Vắng
                                    </span>
                                @endif
                            </td>

                            {{-- ACTION (FORM THUẦN – KHÔNG JS) --}}
                            <td class="text-center">
                                <form
                                    action="{{ route('guide.roll-calls.store', $departure->id) }}"
                                    method="POST"
                                    class="d-inline"
                                >
                                    @csrf
                                    <input type="hidden" name="passenger_id" value="{{ $p->id }}">

                                    {{-- CHECK-IN --}}
                                    <button
                                        type="submit"
                                        name="status"
                                        value="checked_in"
                                        class="btn btn-success btn-sm"
                                        @if($checkIn && $checkIn->status === 'checked_in') disabled @endif
                                    >
                                        <i class="fas fa-check"></i> Check-in
                                    </button>

                                    {{-- ABSENT --}}
                                    <button
                                        type="submit"
                                        name="status"
                                        value="absent"
                                        class="btn btn-danger btn-sm"
                                        @if($checkIn && $checkIn->status === 'absent') disabled @endif
                                    >
                                        <i class="fas fa-times"></i> Vắng
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
</div>
@endsection
