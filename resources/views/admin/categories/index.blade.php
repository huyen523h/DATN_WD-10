@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">Danh mục</h1>
        <a href="{{ route('admin.categories.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded">Tạo danh mục</a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800">{{ session('success') }}</div>
    @endif

    <div class="bg-white shadow rounded">
        <table class="min-w-full text-left">
            <thead>
                <tr>
                    <th class="px-4 py-2">Tên</th>
                    <th class="px-4 py-2">Mô tả</th>
                    <th class="px-4 py-2">Số tour</th>
                    <th class="px-4 py-2">Trạng thái</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $category->name }}</td>
                    <td class="px-4 py-2">{{ $category->description }}</td>
                    <td class="px-4 py-2">{{ $category->tours_count }}</td>
                    <td class="px-4 py-2">{{ $category->status }}</td>
                    <td class="px-4 py-2 text-right">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="px-2 py-1 bg-yellow-500 text-white rounded">Sửa</a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="px-2 py-1 bg-red-600 text-white rounded" onclick="return confirm('Xóa danh mục này?')">Xóa</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">Chưa có danh mục</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $categories->links() }}</div>
</div>
@endsection
