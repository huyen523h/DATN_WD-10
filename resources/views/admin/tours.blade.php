@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 m-0">Quản lý Tours</h1>
        <a href="#" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Thêm tour</a>
    </div>
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead><tr><th>Mã</th><th>Tên tour</th><th>Giá</th><th>Thời lượng</th><th></th></tr></thead>
                <tbody>
                    <tr><td>T001</td><td>Đà Nẵng 3N2Đ</td><td>₫5,500,000</td><td>3 ngày</td><td><a class="btn btn-sm btn-outline-secondary" href="#">Sửa</a></td></tr>
                    <tr><td>T002</td><td>Phú Quốc 4N3Đ</td><td>₫7,800,000</td><td>4 ngày</td><td><a class="btn btn-sm btn-outline-secondary" href="#">Sửa</a></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


