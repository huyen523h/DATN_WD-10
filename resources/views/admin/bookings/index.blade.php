@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">Quản lý đặt tour</h1>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800">{{ session('success') }}</div>
    @endif

    <div class="bg-white shadow rounded">
        <table class="min-w-full text-left">
            <thead>
                <tr>
                    <th class="px-4 py-2">Mã đặt tour</th>
                    <th class="px-4 py-2">Khách hàng</th>
                    <th class="px-4 py-2">Tour</th>
                    <th class="px-4 py-2">Ngày khởi hành</th>
                    <th class="px-4 py-2">Số khách</th>
                    <th class="px-4 py-2">Tổng tiền</th>
                    <th class="px-4 py-2">Trạng thái</th>
                    <th class="px-4 py-2">Ngày đặt</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr class="border-t">
                    <td class="px-4 py-2">#{{ $booking->id }}</td>
                    <td class="px-4 py-2">{{ $booking->user->name }}</td>
                    <td class="px-4 py-2">{{ $booking->tour->title }}</td>
                    <td class="px-4 py-2">{{ $booking->departure->departure_date ?? 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $booking->adults }} người lớn</td>
                    <td class="px-4 py-2">{{ number_format($booking->total_amount, 0) }}đ</td>
                    <td class="px-4 py-2">{{ $booking->status }}</td>
                    <td class="px-4 py-2">{{ $booking->created_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-2 text-right">
                        <form action="{{ route('admin.bookings.update', $booking) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <select name="status" onchange="this.form.submit()" class="px-2 py-1 border rounded">
                                <option value="pending" {{ $booking->status=='pending'?'selected':'' }}>Chờ xác nhận</option>
                                <option value="confirmed" {{ $booking->status=='confirmed'?'selected':'' }}>Đã xác nhận</option>
                                <option value="cancelled" {{ $booking->status=='cancelled'?'selected':'' }}>Đã hủy</option>
                                <option value="completed" {{ $booking->status=='completed'?'selected':'' }}>Hoàn thành</option>
                            </select>
                        </form>
                        <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="px-2 py-1 bg-red-600 text-white rounded" onclick="return confirm('Xóa đặt tour này?')">Xóa</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-4 py-6 text-center text-gray-500">Chưa có đặt tour</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $bookings->links() }}</div>
</div>
@endsection
