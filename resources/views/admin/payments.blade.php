@extends('layouts.admin')

@section('title', 'Quản lý Thanh toán - Admin')

@section('breadcrumb')
<li class="breadcrumb-item active">Quản lý Thanh toán</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-credit-card text-primary"></i> Quản lý Thanh toán</h2>
        <p class="text-muted mb-0">Theo dõi và quản lý các giao dịch thanh toán</p>
    </div>
</div>

<!-- Payments Table -->
<div class="row">
    <div class="col-12">
        <div class="admin-card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    Danh sách thanh toán ({{ $payments->total() }} giao dịch)
                </h6>
            </div>
            <div class="card-body">
                @if($payments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mã giao dịch</th>
                                    <th>Khách hàng</th>
                                    <th>Tour</th>
                                    <th>Số tiền</th>
                                    <th>Phương thức</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày thanh toán</th>
                                    <th width="150">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                    <tr>
                                        <td>
                                            <code>{{ $payment->transaction_id }}</code>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-2">
                                                    {{ strtoupper(substr($payment->booking->user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $payment->booking->user->name }}</div>
                                                    <small class="text-muted">{{ $payment->booking->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $payment->booking->tour->title }}</div>
                                        </td>
                                        <td class="fw-bold text-success">
                                            {{ number_format($payment->amount, 0, ',', '.') }}đ
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                @switch($payment->payment_method)
                                                    @case('cash') Tiền mặt @break
                                                    @case('bank_transfer') Chuyển khoản @break
                                                    @case('momo') MoMo @break
                                                    @case('zalopay') ZaloPay @break
                                                    @default {{ $payment->payment_method }} @break
                                                @endswitch
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge 
                                                @if($payment->status === 'completed') bg-success
                                                @elseif($payment->status === 'pending') bg-warning
                                                @elseif($payment->status === 'failed') bg-danger
                                                @else bg-secondary
                                                @endif">
                                                @switch($payment->status)
                                                    @case('completed') Hoàn thành @break
                                                    @case('pending') Chờ xử lý @break
                                                    @case('failed') Thất bại @break
                                                    @default {{ $payment->status }} @break
                                                @endswitch
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $payment->created_at->format('d/m/Y H:i') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button class="btn btn-outline-info" onclick="viewPayment({{ $payment->id }})" title="Xem">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                @if($payment->status === 'pending')
                                                    <button class="btn btn-outline-success" onclick="approvePayment({{ $payment->id }})" title="Duyệt">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                                <button class="btn btn-outline-danger" onclick="deletePayment({{ $payment->id }})" title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Hiển thị {{ $payments->firstItem() }} đến {{ $payments->lastItem() }} 
                            trong tổng số {{ $payments->total() }} giao dịch
                        </div>
                        <div>
                            {{ $payments->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-credit-card fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Chưa có giao dịch nào</h5>
                        <p class="text-muted">Chưa có thanh toán nào được thực hiện</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.875rem;
}
</style>
@endsection

@section('scripts')
<script>
function viewPayment(paymentId) {
    // Implement view functionality
    console.log('View payment:', paymentId);
}

function approvePayment(paymentId) {
    if (confirm('Bạn có chắc chắn muốn duyệt giao dịch này?')) {
        // Implement approve functionality
        console.log('Approve payment:', paymentId);
    }
}

function deletePayment(paymentId) {
    if (confirm('Bạn có chắc chắn muốn xóa giao dịch này?')) {
        // Implement delete functionality
        console.log('Delete payment:', paymentId);
    }
}
</script>
@endsection
