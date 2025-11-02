@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">Thanh toán</h1>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800">{{ session('success') }}</div>
    @endif

    <div class="bg-white shadow rounded">
        <table class="min-w-full text-left">
            <thead>
                <tr>
                    <th class="px-4 py-2">Mã thanh toán</th>
                    <th class="px-4 py-2">Khách hàng</th>
                    <th class="px-4 py-2">Tour</th>
                    <th class="px-4 py-2">Số tiền</th>
                    <th class="px-4 py-2">Phương thức</th>
                    <th class="px-4 py-2">Trạng thái</th>
                    <th class="px-4 py-2">Ngày tạo</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr class="border-t">
                    <td class="px-4 py-2">#{{ $payment->id }}</td>
                    <td class="px-4 py-2">{{ $payment->booking->user->name }}</td>
                    <td class="px-4 py-2">{{ $payment->booking->tour->title }}</td>
                    <td class="px-4 py-2">{{ number_format($payment->amount, 0) }}đ</td>
                    <td class="px-4 py-2">{{ $payment->payment_method }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 text-xs rounded
                            @if($payment->status == 'completed') bg-green-100 text-green-800
                            @elseif($payment->status == 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ $payment->status }}
                        </span>
                    </td>
                    <td class="px-4 py-2">{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-2 text-right">
                        <form action="{{ route('admin.payments.destroy', $payment) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="px-2 py-1 bg-red-600 text-white rounded" onclick="return confirm('Xóa thanh toán này?')">Xóa</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-6 text-center text-gray-500">Chưa có thanh toán</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $payments->links() }}</div>
</div>
@endsection
