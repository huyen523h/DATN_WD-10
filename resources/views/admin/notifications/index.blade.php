@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">Thông báo</h1>
        <a href="{{ route('admin.notifications.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded">Tạo thông báo</a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800">{{ session('success') }}</div>
    @endif

    <div class="bg-white shadow rounded">
        <table class="min-w-full text-left">
            <thead>
                <tr>
                    <th class="px-4 py-2">Tiêu đề</th>
                    <th class="px-4 py-2">Nội dung</th>
                    <th class="px-4 py-2">Loại</th>
                    <th class="px-4 py-2">Người nhận</th>
                    <th class="px-4 py-2">Đã đọc</th>
                    <th class="px-4 py-2">Ngày tạo</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($notifications as $notification)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $notification->title }}</td>
                    <td class="px-4 py-2">{{ Str::limit($notification->content, 50) }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 text-xs rounded
                            @if($notification->type == 'success') bg-green-100 text-green-800
                            @elseif($notification->type == 'warning') bg-yellow-100 text-yellow-800
                            @elseif($notification->type == 'error') bg-red-100 text-red-800
                            @else bg-blue-100 text-blue-800
                            @endif">
                            {{ $notification->type }}
                        </span>
                    </td>
                    <td class="px-4 py-2">{{ $notification->user ? $notification->user->name : 'Tất cả' }}</td>
                    <td class="px-4 py-2">{{ $notification->is_read ? 'Đã đọc' : 'Chưa đọc' }}</td>
                    <td class="px-4 py-2">{{ $notification->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-2 text-right">
                        <form action="{{ route('admin.notifications.destroy', $notification) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="px-2 py-1 bg-red-600 text-white rounded" onclick="return confirm('Xóa thông báo này?')">Xóa</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">Chưa có thông báo</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $notifications->links() }}</div>
</div>
@endsection
