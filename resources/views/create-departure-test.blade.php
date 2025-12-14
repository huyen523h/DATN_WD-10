<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Tạo Departure Mới</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">
                <i class="fas fa-plus mr-2 text-green-600"></i>
                Test Tạo Departure Mới
            </h1>
            
            <form id="create-form" class="space-y-6">
                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tour ID <span class="text-red-500">*</span></label>
                        <input type="number" id="tour-id" value="15" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ngày khởi hành <span class="text-red-500">*</span></label>
                        <input type="date" id="departure-date" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Giờ khởi hành</label>
                        <input type="time" id="departure-time" value="07:00" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Số chỗ tối đa</label>
                        <input type="number" id="seats-total" value="30" min="1" max="50" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Địa điểm khởi hành</label>
                    <input type="text" id="departure-location" value="Văn phòng công ty - 123 Đường ABC, Quận Ba Đình, Hà Nội" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hướng dẫn khởi hành</label>
                    <textarea id="departure-instructions" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">Quý khách vui lòng có mặt tại điểm tập trung trước 15 phút. Mang theo CMND/CCCD và các giấy tờ cần thiết.</textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Giá tour (VNĐ)</label>
                        <input type="number" id="price" value="1500000" min="0" step="1000" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hướng dẫn viên chính</label>
                        <select id="main-guide" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Chọn hướng dẫn viên chính</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ghi chú đặc biệt</label>
                    <textarea id="special-notes" rows="2" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Ghi chú đặc biệt cho departure này..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="clearForm()" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-eraser mr-2"></i>Xóa form
                    </button>
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        <i class="fas fa-plus mr-2"></i>Tạo departure
                    </button>
                </div>
            </form>
        </div>

        <!-- Test Results -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-clipboard-list mr-2 text-gray-600"></i>
                Kết quả Test
            </h2>
            <div id="test-results" class="bg-gray-50 rounded-lg p-4 min-h-32">
                <p class="text-gray-600">Chưa có test nào được thực hiện.</p>
            </div>
        </div>
    </div>

    <script>
        let testResults = [];

        function addTestResult(message, type = 'info', details = null) {
            const timestamp = new Date().toLocaleTimeString('vi-VN');
            const result = { message, type, details, timestamp };
            testResults.unshift(result);
            updateTestResultsDisplay();
        }

        function updateTestResultsDisplay() {
            const container = document.getElementById('test-results');
            
            if (testResults.length === 0) {
                container.innerHTML = '<p class="text-gray-600">Chưa có test nào được thực hiện.</p>';
                return;
            }

            let html = '';
            testResults.slice(0, 5).forEach(result => {
                const statusClass = result.type === 'success' ? 'text-green-600' : 
                                  result.type === 'error' ? 'text-red-600' : 'text-blue-600';
                const statusIcon = result.type === 'success' ? 'fa-check-circle' : 
                                 result.type === 'error' ? 'fa-times-circle' : 'fa-info-circle';
                
                html += `
                    <div class="flex items-start space-x-3 mb-3 p-2 bg-white rounded border-l-4 ${result.type === 'success' ? 'border-green-500' : result.type === 'error' ? 'border-red-500' : 'border-blue-500'}">
                        <i class="fas ${statusIcon} ${statusClass} mt-1"></i>
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <span class="text-sm text-gray-800">${result.message}</span>
                                <span class="text-xs text-gray-500">${result.timestamp}</span>
                            </div>
                            ${result.details ? `<pre class="text-xs text-gray-500 mt-1 bg-gray-100 p-2 rounded overflow-x-auto">${JSON.stringify(result.details, null, 2)}</pre>` : ''}
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }

        // Load available guides
        async function loadGuides() {
            try {
                const response = await fetch('/api/guides/available');
                const data = await response.json();
                
                if (data.success) {
                    const select = document.getElementById('main-guide');
                    select.innerHTML = '<option value="">Chọn hướng dẫn viên chính</option>';
                    
                    data.data.forEach(guide => {
                        const option = new Option(`${guide.name} (${guide.email})`, guide.id);
                        select.add(option);
                    });
                    
                    addTestResult(`Đã tải ${data.data.length} hướng dẫn viên`, 'success');
                }
            } catch (error) {
                addTestResult('Lỗi tải danh sách HDV: ' + error.message, 'error');
            }
        }

        // Handle form submission
        document.getElementById('create-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = {
                tour_id: document.getElementById('tour-id').value,
                departure_date: document.getElementById('departure-date').value,
                departure_time: document.getElementById('departure-time').value,
                departure_location: document.getElementById('departure-location').value,
                departure_instructions: document.getElementById('departure-instructions').value,
                seats_total: document.getElementById('seats-total').value,
                price: document.getElementById('price').value,
                guide_id: document.getElementById('main-guide').value || null,
                special_notes: document.getElementById('special-notes').value,
                preparation_status: 'pending'
            };

            // Validation
            if (!formData.tour_id || !formData.departure_date) {
                addTestResult('Vui lòng điền đầy đủ thông tin bắt buộc', 'error');
                return;
            }

            try {
                addTestResult('Đang tạo departure mới...', 'info');
                
                const response = await fetch('/api/departures/create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();
                
                if (result.success) {
                    addTestResult(`✅ Đã tạo departure thành công! ID: ${result.data.id}`, 'success', result.data);
                    
                    // Auto-increment date for next test
                    const currentDate = new Date(document.getElementById('departure-date').value);
                    currentDate.setDate(currentDate.getDate() + 1);
                    document.getElementById('departure-date').value = currentDate.toISOString().split('T')[0];
                } else {
                    addTestResult('❌ Lỗi: ' + result.message, 'error', result);
                }
                
            } catch (error) {
                addTestResult('❌ Lỗi kết nối: ' + error.message, 'error');
            }
        });

        function clearForm() {
            document.getElementById('create-form').reset();
            
            // Reset to default values
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            document.getElementById('departure-date').value = tomorrow.toISOString().split('T')[0];
            document.getElementById('departure-time').value = '07:00';
            document.getElementById('seats-total').value = '30';
            document.getElementById('price').value = '1500000';
            
            addTestResult('Đã xóa form', 'info');
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Set default date to tomorrow
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            document.getElementById('departure-date').value = tomorrow.toISOString().split('T')[0];
            
            // Load guides
            loadGuides();
            
            addTestResult('Trang test đã sẵn sàng', 'info');
        });
    </script>
</body>
</html>