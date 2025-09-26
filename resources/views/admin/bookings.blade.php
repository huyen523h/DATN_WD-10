@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <h1 class="h4 mb-3">Đơn đặt tour</h1>
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead><tr><th>Mã</th><th>Khách hàng</th><th>Tour</th><th>Ngày</th><th>Trạng thái</th><th></th></tr></thead>
                <tbody>
                    <tr><td>#B1001</td><td>Phạm Dũng</td><td>Nha Trang 3N2Đ</td><td>05/11/2025</td><td><span class="badge text-bg-warning">Chờ duyệt</span></td><td><a class="btn btn-sm btn-outline-primary" href="#">Xem</a></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


