@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">Đánh giá</h1>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800">{{ session('success') }}</div>
    @endif

    <div class="bg-white shadow rounded">
        <table class="min-w-full text-left">
            <thead>
                <tr>
                    <th class="px-4 py-2">Khách hàng</th>
                    <th class="px-4 py-2">Tour</th>
                    <th class="px-4 py-2">Đánh giá</th>
                    <th class="px-4 py-2">Bình luận</th>
                    <th class="px-4 py-2">Trạng thái</th>
                    <th class="px-4 py-2">Ngày tạo</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $review->user->name }}</td>
                    <td class="px-4 py-2">{{ $review->tour->title }}</td>
                    <td class="px-4 py-2">
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="text-yellow-400">{{ $i <= $review->rating ? '★' : '☆' }}</span>
                            @endfor
                        </div>
                    </td>
                    <td class="px-4 py-2">{{ Str::limit($review->comment, 50) }}</td>
                    <td class="px-4 py-2">{{ $review->status }}</td>
                    <td class="px-4 py-2">{{ $review->created_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-2 text-right">
                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="px-2 py-1 bg-red-600 text-white rounded" onclick="return confirm('Xóa đánh giá này?')">Xóa</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">Chưa có đánh giá</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $reviews->links() }}</div>
</div>
@endsection
