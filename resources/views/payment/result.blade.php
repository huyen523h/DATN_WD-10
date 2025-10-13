@extends('layouts.app')

@section('content')
<div class="container text-center mt-5">
    @if($success)
        <h2 class="text-success">Thanh toán {{ $method }} thành công!</h2>
    @else
        <h2 class="text-danger">Thanh toán {{ $method }} thất bại!</h2>
    @endif
    <a href="{{ route('bookings.index') }}" class="btn btn-primary mt-3">Quay lại đơn đặt tour</a>
</div>
@endsection
