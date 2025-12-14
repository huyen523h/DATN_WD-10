<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quick Test - Thêm Lịch trình</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-xl font-bold mb-4">Quick Test - Thêm Lịch trình</h1>
        
        <form id="test-form" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Tour ID</label>
                <input type="number" id="tour-id" value="14" class="w-full border rounded px-3 py-2">
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-1">Ngày thứ</label>
                <input type="number" id="day-number" value="7" class="w-full border rounded px-3 py-2">
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-1">Tiêu đề</label>
                <input type="text" id="title" value="Ngày test mới" class="w-full border rounded px-3 py-2">
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-1">Mô tả</label>
                <textarea id="description" class="w-full border rounded px-3 py-2">Mô tả test</textarea>
            </div>
            
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                Thêm Lịch trình
            </button>
        </form>
        
        <div id="result" class="mt-4 p-3 rounded hidden"></div>
    </div>

    <script>
        document.getElementById('test-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const tourId = document.getElementById('tour-id').value;
            const formData = {
                day_number: parseInt(document.getElementById('day-number').value),
                title: document.getElementById('title').value,
                description: document.getElementById('description').value
            };
            
            const resultDiv = document.getElementById('result');
            resultDiv.classList.remove('hidden');
            resultDiv.textContent = 'Đang gửi...';
            resultDiv.className = 'mt-4 p-3 rounded bg-blue-100 text-blue-800';
            
            try {
                const response = await fetch(`/api/schedule-create/${tourId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    resultDiv.textContent = '✅ ' + result.message;
                    resultDiv.className = 'mt-4 p-3 rounded bg-green-100 text-green-800';
                    
                    // Tăng day number cho lần test tiếp theo
                    document.getElementById('day-number').value = parseInt(document.getElementById('day-number').value) + 1;
                } else {
                    resultDiv.textContent = '❌ ' + result.message;
                    resultDiv.className = 'mt-4 p-3 rounded bg-red-100 text-red-800';
                }
            } catch (error) {
                resultDiv.textContent = '❌ Lỗi: ' + error.message;
                resultDiv.className = 'mt-4 p-3 rounded bg-red-100 text-red-800';
            }
        });
    </script>
</body>
</html>