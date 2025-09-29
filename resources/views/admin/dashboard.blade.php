@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h4 m-0">Dashboard</h1>
    </div>

    <div class="row g-3 g-lg-4">
        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Tổng tour</div>
                            <div class="fs-4">128</div>
                        </div>
                        <i class="bi bi-map fs-1 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Đặt tour hôm nay</div>
                            <div class="fs-4">24</div>
                        </div>
                        <i class="bi bi-journal-check fs-1 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Khách hàng</div>
                            <div class="fs-4">3,245</div>
                        </div>
                        <i class="bi bi-people fs-1 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Doanh thu tháng</div>
                            <div class="fs-4">₫256,000,000</div>
                        </div>
                        <i class="bi bi-cash-coin fs-1 text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-header bg-white"><strong>Đơn đặt tour mới</strong></div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead><tr><th>Mã</th><th>Khách hàng</th><th>Tour</th><th>Ngày khởi hành</th><th>Trạng thái</th><th></th></tr></thead>
                <tbody>
                    <tr><td>#B1234</td><td>Nguyễn An</td><td>Đà Nẵng 3N2Đ</td><td>12/10/2025</td><td><span class="badge text-bg-warning">Chờ duyệt</span></td><td><a class="btn btn-sm btn-outline-primary" href="#">Xem</a></td></tr>
                    <tr><td>#B1235</td><td>Trần Bình</td><td>Phú Quốc 4N3Đ</td><td>15/10/2025</td><td><span class="badge text-bg-success">Đã xác nhận</span></td><td><a class="btn btn-sm btn-outline-primary" href="#">Xem</a></td></tr>
                    <tr><td>#B1236</td><td>Lê Chi</td><td>Sapa 2N1Đ</td><td>18/10/2025</td><td><span class="badge text-bg-secondary">Hủy</span></td><td><a class="btn btn-sm btn-outline-primary" href="#">Xem</a></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


