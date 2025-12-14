<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Tạo HDV</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">
                <i class="fas fa-user-plus mr-2 text-green-600"></i>
                Test Tạo Hướng Dẫn Viên
            </h1>
            
            <form action="/admin/guides" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Họ tên *</label>
                        <input type="text" name="full_name" class="w-full border border-gray-300 rounded-md px-3 py-2" required placeholder="Nhập họ tên HDV" value="Nguyễn Văn Test">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" name="email" class="w-full border border-gray-300 rounded-md px-3 py-2" required placeholder="email@example.com" value="test{{ rand(1000, 9999) }}@example.com">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại</label>
                        <input type="text" name="phone" class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="0123456789" value="0909{{ rand(100000, 999999) }}">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Giới tính</label>
                        <select name="gender" class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="">Chọn giới tính</option>
                            <option value="male">Nam</option>
                            <option value="female" selected>Nữ</option>
                            <option value="other">Khác</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Số năm kinh nghiệm</label>
                        <input type="number" name="experience_years" class="w-full border border-gray-300 rounded-md px-3 py-2" min="0" value="2">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ngôn ngữ chính</label>
                        <input type="text" name="primary_language" class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="Tiếng Việt" value="Tiếng Việt">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ</label>
                    <textarea name="address" class="w-full border border-gray-300 rounded-md px-3 py-2" rows="2" placeholder="Nhập địa chỉ">Hà Nội, Việt Nam</textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tiểu sử</label>
                    <textarea name="biography" class="w-full border border-gray-300 rounded-md px-3 py-2" rows="3" placeholder="Mô tả về HDV">Hướng dẫn viên có kinh nghiệm trong lĩnh vực du lịch.</textarea>
                </div>
                
                <div class="flex justify-end space-x-4">
                    <a href="/admin/guides" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i>Quay lại
                    </a>
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        <i class="fas fa-save mr-2"></i>Tạo HDV
                    </button>
                </div>
            </form>
        </div>

        @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <h3 class="text-red-800 font-medium mb-2">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Có lỗi xảy ra:
            </h3>
            <ul class="text-red-700 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if (session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="text-green-800">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
        @endif

        @if (session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="text-red-800">
                <i class="fas fa-times-circle mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
        @endif
    </div>
</body>
</html>