@extends('layouts.admin')

@section('title', 'Chi tiết khách hàng')

@section('content')
    <div class="container">

        <h3 class="fw-bold mb-3">
            <i class="fas fa-suitcase-rolling text-primary me-2"></i>
            Các tour đã đi
        </h3>

        @forelse ($customer->bookings as $booking)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="fw-bold mb-1">
                        {{ $booking->tour->title ?? '---' }}
                    </h5>
                    <small class="text-muted">
                        Ngày đặt: {{ $booking->created_at?->format('d/m/Y') ?? '—' }} |
                        Khởi hành: {{ $booking->departure?->departure_date?->format('d/m/Y') ?? '—' }}
                    </small>
                </div>

                <div class="card-body">

                    <p><strong>Tổng tiền:</strong> {{ number_format($booking->total_amount) }}đ</p>

                    <h6 class="fw-bold mt-3 mb-2">
                        <i class="fas fa-users text-success me-1"></i>
                        Danh sách hành khách
                    </h6>

                    @if ($booking->passengers->count())
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th></th>
                                    <th>Họ tên</th>
                                    <th>Giới tính</th>
                                    <th>Năm sinh</th>
                                    <th>CCCD / Passport</th>
                                    <th>Loại</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($booking->passengers as $p)
                                    <tr>
                                        <td>{{ $p->full_name ?? '—' }}</td>
                                        <td>{{ $p->gender ?? '—' }}</td>
                                        <td>{{ $p->birth_year ?? '—' }}</td>
                                        <td>{{ $p->id_number ?? '—' }}</td>
                                        <td>
                                            <span class="badge bg-info text-white">
                                                {{ strtoupper($p->passenger_type ?? '—') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">Không có hành khách.</p>
                    @endif

                </div>
            </div>

        @empty
            <p class="text-muted">Khách hàng chưa đặt tour nào.</p>
        @endforelse

    @endsection
