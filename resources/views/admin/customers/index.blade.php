@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">Khách hàng</h1>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800">{{ session('success') }}</div>
    @endif

    <div class="bg-white shadow rounded">
        <table class="min-w-full text-left">
            <thead>
                <tr>
                    <th class="px-4 py-2">Tên</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Số điện thoại</th>
                    <th class="px-4 py-2">Số đặt tour</th>
                    <th class="px-4 py-2">Ngày đăng ký</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $customer->name }}</td>
                    <td class="px-4 py-2">{{ $customer->email }}</td>
                    <td class="px-4 py-2">{{ $customer->phone }}</td>
                    <td class="px-4 py-2">{{ $customer->bookings->count() }}</td>
                    <td class="px-4 py-2">{{ $customer->created_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-2 text-right">
                        <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="px-2 py-1 bg-red-600 text-white rounded" onclick="return confirm('Xóa khách hàng này?')">Xóa</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">Chưa có khách hàng</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $customers->links() }}</div>
</div>
@endsection
