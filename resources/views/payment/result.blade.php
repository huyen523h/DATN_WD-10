@extends('layouts.app')

@section('content')
<div class="container text-center mt-5">
    <h2>Thông tin thanh toán MoMo</h2>

    <div class="card shadow-sm p-4 mt-4 mx-auto" style="max-width: 500px;">
        <p><strong>Mã đơn hàng:</strong> {{ $data['orderId'] ?? 'N/A' }}</p>
        <p><strong>Số tiền:</strong> {{ number_format($data['amount'] ?? 0, 0, ',', '.') }} VND</p>
        <p><strong>Mã giao dịch MoMo:</strong> {{ $data['transId'] ?? 'N/A' }}</p>
        <p><strong>Mã phản hồi:</strong> {{ $data['resultCode'] ?? 'N/A' }}</p>
        <p><strong>Thông điệp:</strong> {{ $data['message'] ?? 'Không có thông tin' }}</p>

        @if(isset($data['resultCode']) && $data['resultCode'] == 0)
            <h3 class="text-success mt-4">Thanh toán thành công</h3>
        @else
            <h3 class="text-danger mt-4">Thanh toán thất bại</h3>
        @endif

        <a href="/" class="btn btn-primary mt-4">Quay lại trang chủ</a>
    </div>
</div>
@endsection
