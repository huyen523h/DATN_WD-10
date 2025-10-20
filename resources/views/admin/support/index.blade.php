@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">Hỗ trợ</h1>
        <a href="{{ route('admin.support.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded">Tạo ticket</a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800">{{ session('success') }}</div>
    @endif

    <div class="bg-white shadow rounded">
        <table class="min-w-full text-left">
            <thead>
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Tiêu đề</th>
                    <th class="px-4 py-2">Người gửi</th>
                    <th class="px-4 py-2">Trạng thái</th>
                    <th class="px-4 py-2">Độ ưu tiên</th>
                    <th class="px-4 py-2">Ngày tạo</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                <tr class="border-t">
                    <td class="px-4 py-2">#{{ $ticket->id }}</td>
                    <td class="px-4 py-2">{{ $ticket->title }}</td>
                    <td class="px-4 py-2">{{ $ticket->user->name }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 text-xs rounded
                            @if($ticket->status == 'open') bg-blue-100 text-blue-800
                            @elseif($ticket->status == 'in_progress') bg-yellow-100 text-yellow-800
                            @elseif($ticket->status == 'resolved') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $ticket->status }}
                        </span>
                    </td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 text-xs rounded
                            @if($ticket->priority == 'high') bg-red-100 text-red-800
                            @elseif($ticket->priority == 'medium') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800
                            @endif">
                            {{ $ticket->priority }}
                        </span>
                    </td>
                    <td class="px-4 py-2">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-2 text-right">
                        <form action="{{ route('admin.support.destroy', $ticket) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="px-2 py-1 bg-red-600 text-white rounded" onclick="return confirm('Xóa ticket này?')">Xóa</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">Chưa có ticket hỗ trợ</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $tickets->links() }}</div>
</div>
@endsection
