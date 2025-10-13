@extends('layouts.admin')

@section('title', 'Quản lý Khách hàng - Tour365')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-users"></i> Quản lý Khách hàng</h2>
                <div class="btn-group">
                    <button class="btn btn-outline-primary" onclick="filterCustomers('all')">
                        <i class="fas fa-list"></i> Tất cả
                    </button>
                    <button class="btn btn-outline-success" onclick="filterCustomers('active')">
                        <i class="fas fa-user-check"></i> Hoạt động
                    </button>
                    <button class="btn btn-outline-warning" onclick="filterCustomers('new')">
                        <i class="fas fa-user-plus"></i> Mới đăng ký
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list"></i> Danh sách khách hàng
                    </h6>
                </div>
                <div class="card-body">
                    @if($customers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Thông tin</th>
                                        <th>Liên hệ</th>
                                        <th>Số đặt tour</th>
                                        <th>Tổng chi tiêu</th>
                                        <th>Ngày đăng ký</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customers as $customer)
                                        <tr data-status="{{ $customer->bookings->count() > 0 ? 'active' : 'new' }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-3">
                                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px;">
                                                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $customer->name }}</strong><br>
                                                        <small class="text-muted">ID: #{{ $customer->id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <i class="fas fa-envelope text-muted"></i> {{ $customer->email }}<br>
                                                    @if($customer->phone)
                                                        <i class="fas fa-phone text-muted"></i> {{ $customer->phone }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $customer->bookings->count() }} đặt tour
                                                </span>
                                            </td>
                                            <td>
                                                <strong class="text-success">
                                                    {{ number_format($customer->bookings->sum('total_amount'), 0, ',', '.') }}đ
                                                </strong>
                                            </td>
                                            <td>{{ $customer->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                @if($customer->bookings->count() > 0)
                                                    <span class="badge bg-success">Hoạt động</span>
                                                @else
                                                    <span class="badge bg-warning">Mới đăng ký</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-outline-info" 
                                                            onclick="viewCustomerDetails({{ $customer->id }})" 
                                                            title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-primary" 
                                                            onclick="viewCustomerBookings({{ $customer->id }})" 
                                                            title="Xem đặt tour">
                                                        <i class="fas fa-calendar-check"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-warning" 
                                                            onclick="editCustomer({{ $customer->id }})" 
                                                            title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $customers->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-4x text-muted mb-4"></i>
                            <h4>Chưa có khách hàng nào</h4>
                            <p class="text-muted">Chưa có khách hàng nào đăng ký</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Customer Details Modal -->
<div class="modal fade" id="customerDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user"></i> Chi tiết khách hàng
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="customerDetailsContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Customer Bookings Modal -->
<div class="modal fade" id="customerBookingsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-check"></i> Đặt tour của khách hàng
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="customerBookingsContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function filterCustomers(status) {
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        if (status === 'all' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function viewCustomerDetails(customerId) {
    // Placeholder for customer details
    document.getElementById('customerDetailsContent').innerHTML = `
        <div class="text-center py-4">
            <i class="fas fa-spinner fa-spin fa-2x text-muted mb-3"></i>
            <p>Đang tải thông tin khách hàng...</p>
        </div>
    `;
    
    new bootstrap.Modal(document.getElementById('customerDetailsModal')).show();
    
    // Simulate loading
    setTimeout(() => {
        document.getElementById('customerDetailsContent').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6>Thông tin cá nhân</h6>
                    <p><strong>Tên:</strong> Khách hàng #${customerId}</p>
                    <p><strong>Email:</strong> customer${customerId}@example.com</p>
                    <p><strong>Điện thoại:</strong> 0901234567</p>
                </div>
                <div class="col-md-6">
                    <h6>Thống kê</h6>
                    <p><strong>Tổng đặt tour:</strong> 5</p>
                    <p><strong>Tổng chi tiêu:</strong> 15,000,000đ</p>
                    <p><strong>Ngày đăng ký:</strong> 01/01/2025</p>
                </div>
            </div>
        `;
    }, 1000);
}

function viewCustomerBookings(customerId) {
    // Placeholder for customer bookings
    document.getElementById('customerBookingsContent').innerHTML = `
        <div class="text-center py-4">
            <i class="fas fa-spinner fa-spin fa-2x text-muted mb-3"></i>
            <p>Đang tải danh sách đặt tour...</p>
        </div>
    `;
    
    new bootstrap.Modal(document.getElementById('customerBookingsModal')).show();
    
    // Simulate loading
    setTimeout(() => {
        document.getElementById('customerBookingsContent').innerHTML = `
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Mã đặt tour</th>
                            <th>Tour</th>
                            <th>Ngày khởi hành</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#000001</td>
                            <td>Tour Đà Nẵng - Hội An</td>
                            <td>15/10/2025</td>
                            <td>2,500,000đ</td>
                            <td><span class="badge bg-success">Đã xác nhận</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;
    }, 1000);
}

function editCustomer(customerId) {
    alert(`Chỉnh sửa thông tin khách hàng #${customerId}`);
}
</script>
@endsection

@section('styles')
<style>
.avatar {
    flex-shrink: 0;
}
</style>
@endsection