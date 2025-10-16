@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-semibold mb-4">Tạo thông báo</h1>

    @if($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.notifications.store') }}" method="POST" class="bg-white shadow rounded p-4 space-y-4">
        @csrf
        <div>
            <label class="block mb-1">Tiêu đề</label>
            <input name="title" value="{{ old('title') }}" class="w-full border px-3 py-2 rounded" required />
        </div>
        <div>
            <label class="block mb-1">Nội dung</label>
            <textarea name="content" class="w-full border px-3 py-2 rounded" rows="4" required>{{ old('content') }}</textarea>
        </div>
        <div>
            <label class="block mb-1">Loại thông báo</label>
            <select name="type" class="w-full border px-3 py-2 rounded">
                <option value="info" {{ old('type')=='info'?'selected':'' }}>Thông tin</option>
                <option value="success" {{ old('type')=='success'?'selected':'' }}>Thành công</option>
                <option value="warning" {{ old('type')=='warning'?'selected':'' }}>Cảnh báo</option>
                <option value="error" {{ old('type')=='error'?'selected':'' }}>Lỗi</option>
            </select>
        </div>
        <div>
            <label class="block mb-1">Gửi đến người dùng (để trống = gửi tất cả)</label>
            <select name="user_id" class="w-full border px-3 py-2 rounded">
                <option value="">Tất cả người dùng</option>
                @foreach($users as $user)
                <option value="{{ $user->id }}" {{ old('user_id')==$user->id?'selected':'' }}>{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded">Gửi thông báo</button>
            <a href="{{ route('admin.notifications') }}" class="px-4 py-2 bg-gray-200 rounded">Hủy</a>
        </div>
    </form>
</div>
@endsection
