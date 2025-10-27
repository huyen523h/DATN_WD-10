    <style>
        /* Giữ bảng thẳng hàng, không bị lệch */
        .table {
            table-layout: fixed;
            /* các cột sẽ có độ rộng cố định */
            width: 100%;
            border-collapse: collapse;
        }

        /* Căn giữa nội dung */
        .table th,
        .table td {
            text-align: center;
            vertical-align: middle;
            white-space: normal;
            /* cho phép xuống dòng */
            word-wrap: break-word;
            padding: 8px 10px;
        }

        /* Xóa hiệu ứng ảo gây lệch */
        .table tr::before,
        .table tr::after {
            content: none !important;
        }

        /* Nếu cột TOUR quá dài thì fix chiều rộng lại */
        .table th:nth-child(2),
        .table td:nth-child(2) {
            width: 180px;
        }

        /* Chống lệch do badge hoặc nút */
        .table td span.badge,
        .table td .btn {
            vertical-align: middle !important;
        }
    </style>
    @extends('layouts.admin')

    @section('title', 'Danh sách lịch khởi hành')

    @section('content')
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold text-primary">Danh sách lịch khởi hành</h4>
                <a href="{{ route('admin.departures.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm lịch khởi hành
                </a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <table class="table table-hover align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>TOUR</th>
                                <th>NGÀY KHỞI HÀNH</th>
                                <th>TỔNG GHẾ</th>
                                <th>GHẾ TRỐNG</th>
                                <th>GIÁ NGƯỜI LỚN</th>
                                <th>GIÁ TRẺ EM</th>
                                <th>GIÁ EM BÉ</th>
                                <th>TRẠNG THÁI</th>
                                <th>NGÀY TẠO</th>
                                <th>THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departures as $departure)
                                <tr>
                                    <td>{{ $departure->id }}</td>
                                    <td>{{ $departure->tour->title ?? 'Chưa xác định' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($departure->departure_date)->format('d/m/Y') }}</td>
                                    <td>{{ $departure->seats_total }}</td>
                                    <td>{{ $departure->seats_available }}</td>
                                    <td>{{ number_format($departure->price, 0, ',', '.') }}₫</td>
                                    <td>{{ number_format($departure->child_price, 0, ',', '.') }}₫</td>
                                    <td>{{ number_format($departure->infant_price, 0, ',', '.') }}₫</td>
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
                                    <td>{{ $departure->created_at ? $departure->created_at->format('d/m/Y') : '---' }}</td>
                                    <td>
                                        <a href="{{ route('admin.departures.show', $departure->id) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.departures.edit', $departure->id) }}"
                                            class="btn btn-sm btn-warning text-white">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.departures.destroy', $departure->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('Bạn có chắc muốn xóa lịch này?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $departures->links() }}
                    </div>
                </div>
            </div>
        </div>
    @endsection
