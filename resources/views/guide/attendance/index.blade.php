@extends('layouts.app')

@section('title', 'Chấm công tour')

@section('content')
<div class="container py-5">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-primary">
                🕒 Chấm công tour
            </h2>
            <p class="text-muted">
                {{ $departure->tour->title }} –
                Khởi hành {{ $departure->departure_date->format('d/m/Y') }}
            </p>
        </div>

        <a href="{{ route('guide.departures.show', $departure->id) }}"
           class="btn btn-outline-secondary">
            ← Quay lại
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- BẢNG CHẤM CÔNG -->
    <form method="POST">
        @csrf

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Ngày</th>
                            <th>Trạng thái</th>
                            <th>Phụ cấp (₫)</th>
                            <th>Trừ (₫)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dates as $date)
                            @php
                                $key = $date->format('Y-m-d');
                                $att = $attendances[$key] ?? null;
                            @endphp
                            <tr>
                                <td>
                                    {{ $date->format('d/m/Y') }}
                                </td>

                                <td>
                                    <select
                                        name="attendance[{{ $key }}][status]"
                                        class="form-select"
                                    >
                                        <option value="present"
                                            @selected(optional($att)->status === 'present')>
                                            Có mặt
                                        </option>
                                        <option value="late"
                                            @selected(optional($att)->status === 'late')>
                                            Đi trễ
                                        </option>
                                        <option value="absent"
                                            @selected(optional($att)->status === 'absent')>
                                            Vắng
                                        </option>
                                    </select>
                                </td>

                                <td>
                                    <input type="number"
                                           class="form-control"
                                           name="attendance[{{ $key }}][bonus]"
                                           value="{{ $att->bonus ?? 0 }}">
                                </td>

                                <td>
                                    <input type="number"
                                           class="form-control"
                                           name="attendance[{{ $key }}][penalty]"
                                           value="{{ $att->penalty ?? 0 }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 text-end">
            <button class="btn btn-success px-4">
                💾 Lưu chấm công
            </button>
        </div>
    </form>

</div>
@endsection
