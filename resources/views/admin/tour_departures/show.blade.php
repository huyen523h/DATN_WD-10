@extends('layouts.admin')

@section('title', 'Chi tiết lịch khởi hành')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-primary">
                <i class="fas fa-calendar-day"></i> Chi tiết lịch khởi hành #{{ $departure->id }}
            </h4>
            <a href="{{ route('admin.departures.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table class="table table-bordered align-middle text-center">
                    <tbody>
                        <tr>
                            <th>ID</th>
                            <td>{{ $departure->id }}</td>
                        </tr>
                        <tr>
                            <th>TOUR</th>
                            <td>{{ $departure->tour->title ?? 'Chưa xác định' }}</td>
                        </tr>
                        <tr>
                            <th>NGÀY KHỞI HÀNH</th>
                            <td>{{ \Carbon\Carbon::parse($departure->departure_date)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>TỔNG GHẾ</th>
                            <td>{{ $departure->seats_total }}</td>
                        </tr>
                        <tr>
                            <th>GHẾ TRỐNG</th>
                            <td>{{ $departure->seats_available }}</td>
                        </tr>
                        <tr>
                            <th>GIÁ NGƯỜI LỚN</th>
                            <td>{{ number_format($departure->price, 0, ',', '.') }}₫</td>
                        </tr>
                        <tr>
                            <th>GIÁ TRẺ EM</th>
                            <td>{{ number_format($departure->child_price, 0, ',', '.') }}₫</td>
                        </tr>
                        <tr>
                            <th>GIÁ EM BÉ</th>
                            <td>{{ number_format($departure->infant_price, 0, ',', '.') }}₫</td>
                        </tr>
                        <tr>
                            <th>TRẠNG THÁI</th>
                            <td>
                                @if ($departure->status === 'available')
                                    <span class="badge bg-success">Còn chỗ</span>
                                @elseif ($departure->status === 'contact')
                                    <span class="badge bg-warning text-dark">Liên hệ</span>
                                @elseif ($departure->status === 'sold_out')
                                    <span class="badge bg-danger">Hết chỗ</span>
                                @else
                                    <span class="badge bg-secondary">Không xác định</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>NGÀY TẠO</th>
                            <td>{{ $departure->created_at ? $departure->created_at->format('d/m/Y H:i') : '---' }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('admin.departures.edit', $departure->id) }}" class="btn btn-warning text-white me-2">
                        <i class="fas fa-edit"></i> Chỉnh sửa
                    </a>
                    <form action="{{ route('admin.departures.destroy', $departure->id) }}" method="POST"
                        onsubmit="return confirm('Bạn có chắc muốn xóa lịch này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt"></i> Xóa
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
