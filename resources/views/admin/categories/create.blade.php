@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-semibold mb-4">Tạo danh mục</h1>

    @if($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.categories.store') }}" method="POST" class="bg-white shadow rounded p-4 space-y-4">
        @csrf
        <div>
            <label class="block mb-1">Tên danh mục</label>
            <input name="name" value="{{ old('name') }}" class="w-full border px-3 py-2 rounded" required />
        </div>
        <div>
            <label class="block mb-1">Mô tả</label>
            <textarea name="description" class="w-full border px-3 py-2 rounded">{{ old('description') }}</textarea>
        </div>
        <div>
            <label class="block mb-1">URL hình ảnh</label>
            <input name="image_url" value="{{ old('image_url') }}" class="w-full border px-3 py-2 rounded" />
        </div>
        <div>
            <label class="block mb-1">Trạng thái</label>
            <select name="status" class="w-full border px-3 py-2 rounded">
                <option value="active" {{ old('status')=='active'?'selected':'' }}>active</option>
                <option value="inactive" {{ old('status')=='inactive'?'selected':'' }}>inactive</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded">Lưu</button>
            <a href="{{ route('admin.categories') }}" class="px-4 py-2 bg-gray-200 rounded">Hủy</a>
        </div>
    </form>
</div>
@endsection
