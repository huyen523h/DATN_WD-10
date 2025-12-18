@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-semibold mb-4">Tạo ticket hỗ trợ</h1>

    @if($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.support.store') }}" method="POST" class="bg-white shadow rounded p-4 space-y-4">
        @csrf
        <div>
            <label class="block mb-1">Tiêu đề</label>
            <input name="title" value="{{ old('title') }}" class="w-full border px-3 py-2 rounded" required />
        </div>
        <div>
            <label class="block mb-1">Mô tả vấn đề</label>
            <textarea name="description" class="w-full border px-3 py-2 rounded" rows="4" required>{{ old('description') }}</textarea>
        </div>
        <div>
            <label class="block mb-1">Người gửi</label>
            <select name="user_id" class="w-full border px-3 py-2 rounded" required>
                <option value="">Chọn người dùng</option>
                @foreach($users as $user)
                <option value="{{ $user->id }}" {{ old('user_id')==$user->id?'selected':'' }}>{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block mb-1">Độ ưu tiên</label>
            <select name="priority" class="w-full border px-3 py-2 rounded">
                <option value="low" {{ old('priority')=='low'?'selected':'' }}>Thấp</option>
                <option value="medium" {{ old('priority')=='medium'?'selected':'' }}>Trung bình</option>
                <option value="high" {{ old('priority')=='high'?'selected':'' }}>Cao</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded">Tạo ticket</button>
            <a href="{{ route('admin.support') }}" class="px-4 py-2 bg-gray-200 rounded">Hủy</a>
        </div>
    </form>
</div>
@endsection
