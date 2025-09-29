@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <h1 class="h4 mb-3">Khách hàng</h1>
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead><tr><th>Mã KH</th><th>Tên</th><th>Email</th><th>Điện thoại</th><th></th></tr></thead>
                <tbody>
                    <tr><td>C001</td><td>Ngô Hạnh</td><td>hanh@example.com</td><td>0901 234 567</td><td><a class="btn btn-sm btn-outline-secondary" href="#">Chi tiết</a></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


