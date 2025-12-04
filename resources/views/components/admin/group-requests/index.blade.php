@extends('layouts.admin')

@section('title', 'Quản lý Yêu cầu Tour Đoàn')

@section('content')
<div class="container-fluid p-4">
    <h1 class="h3 mb-4 text-gray-800">Yêu cầu Tour Đoàn</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Khách hàng</th>
                            <th>Nhu cầu</th>
                            <th>Quy mô</th>
                            <th>Ngân sách</th>
                            <th>Trạng thái</th>
                            <th>Ngày gửi</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $req)
                        <tr>
                            <td>#{{ $req->id }}</td>
                            <td>
                                <strong>{{ $req->name }}</strong><br>
                                <small>{{ $req->phone }}</small>
                            </td>
                            <td>
                                {{ $req->destination }}<br>
                                <small class="text-muted">Khởi hành: {{ $req->departure_date->format('d/m/Y') }}</small>
                            </td>
                            <td>
                                {{ $req->adults }} lớn, {{ $req->children }} trẻ
                            </td>
                            <td>{{ $req->budget }}</td>
                            <td>
                                @if($req->status == 'pending')
                                    <span class="badge bg-warning text-dark">Mới</span>
                                @elseif($req->status == 'contacted')
                                    <span class="badge bg-primary">Đang tư vấn</span>
                                @elseif($req->status == 'contracted')
                                    <span class="badge bg-success">Đã chốt</span>
                                @else
                                    <span class="badge bg-danger">Hủy/Trượt</span>
                                @endif
                            </td>
                            <td>{{ $req->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.group-requests.show', $req->id) }}" class="btn btn-sm btn-info text-white">
                                    <i class="fas fa-eye"></i> Chi tiết
                                </a>    
                                <form action="{{ route('admin.group-requests.destroy', $req->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $requests->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection