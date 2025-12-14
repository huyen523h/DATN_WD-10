<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Cập Nhật Trạng Thái Departure</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">
                <i class="fas fa-tasks mr-2 text-purple-600"></i>
                Test Cập Nhật Trạng Thái Departure
            </h1>
            
            <!-- Departure Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Departure ID</label>
                    <input type="number" id="departure-id" value="50" class="w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                <div class="flex items-end">
                    <button onclick="loadDepartureInfo()" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        <i class="fas fa-search mr-2"></i>Tải thông tin
                    </button>
                </div>
            </div>
        </div>

        <!-- Current Status -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                Thông Tin Hiện Tại
            </h2>
            <div id="current-info" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-2xl text-gray-400 mb-2"></i>
                    <p class="text-gray-600">Chưa tải dữ liệu...</p>
                </div>
            </div>
        </div>

        <!-- Status Update Actions -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-edit mr-2 text-green-600"></i>
                Cập Nhật Trạng Thái
            </h2>
            
            <!-- Quick Actions -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
                <button onclick="updateStatus('pending')" class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg hover:bg-yellow-100 transition-colors text-center">
                    <i class="fas fa-clock text-yellow-600 text-xl mb-2"></i>
                    <div class="text-sm font-medium text-yellow-800">Đang chuẩn bị</div>
                </button>
                
                <button onclick="updateStatus('ready')" class="p-3 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition-colors text-center">
                    <i class="fas fa-check text-green-600 text-xl mb-2"></i>
                    <div class="text-sm font-medium text-green-800">Sẵn sàng</div>
                </button>
                
                <button onclick="updateStatus('confirmed')" class="p-3 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors text-center">
                    <i class="fas fa-check-double text-blue-600 text-xl mb-2"></i>
                    <div class="text-sm font-medium text-blue-800">Đã xác nhận</div>
                </button>
                
                <button onclick="updateStatus('cancelled')" class="p-3 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors text-center">
                    <i class="fas fa-times text-red-600 text-xl mb-2"></i>
                    <div class="text-sm font-medium text-red-800">Đã hủy</div>
                </button>
                
                <button onclick="updateStatus('draft')" class="p-3 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors text-center">
                    <i class="fas fa-file-alt text-gray-600 text-xl mb-2"></i>
                    <div class="text-sm font-medium text-gray-800">Nháp</div>
                </button>
            </div>
            
            <!-- Custom Status -->
            <div class="flex space-x-2">
                <select id="custom-status" class="flex-1 border border-gray-300 rounded-md px-3 py-2">
                    <option value="pending">Đang chuẩn bị</option>
                    <option value="ready">Sẵn sàng</option>
                    <option value="confirmed">Đã xác nhận</option>
                    <option value="cancelled">Đã hủy</option>
                    <option value="draft">Nháp</option>
                </select>
                <button onclick="updateStatus(document.getElementById('custom-status').value)" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700">
                    <i class="fas fa-sync mr-2"></i>Cập nhật
                </button>
            </div>
        </div>

        <!-- Test Results -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-clipboard-list mr-2 text-gray-600"></i>
                Lịch Sử Cập Nhật
            </h2>
            <div id="test-results" class="bg-gray-50 rounded-lg p-4 min-h-32 max-h-64 overflow-y-auto">
                <p class="text-gray-600">Chưa có cập nhật nào...</p>
            </div>
        </div>
    </div>

    <script>
        let testResults = [];
        let currentDeparture = null;

        function addTestResult(message, type = 'info', details = null) {
            const timestamp = new Date().toLocaleTimeString('vi-VN');
            const result = { message, type, details, timestamp };
            testResults.unshift(result);
            updateTestResultsDisplay();
        }

        function updateTestResultsDisplay() {
            const container = document.getElementById('test-results');
            
            if (testResults.length === 0) {
                container.innerHTML = '<p class="text-gray-600">Chưa có cập nhật nào...</p>';
                return;
            }

            let html = '';
            testResults.slice(0, 10).forEach(result => {
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

        async function loadDepartureInfo() {
            const departureId = document.getElementById('departure-id').value;
            
            if (!departureId) {
                addTestResult('Vui lòng nhập Departure ID', 'error');
                return;
            }

            try {
                addTestResult(`Đang tải thông tin departure ${departureId}...`, 'info');
                
                const response = await fetch(`/api/departures/${departureId}`);
                const data = await response.json();
                
                if (data.success) {
                    currentDeparture = data.data;
                    displayDepartureInfo(data.data);
                    addTestResult('✅ Đã tải thông tin departure thành công', 'success');
                } else {
                    addTestResult('❌ Lỗi: ' + data.message, 'error');
                }
            } catch (error) {
                addTestResult('❌ Lỗi kết nối: ' + error.message, 'error');
            }
        }

        function displayDepartureInfo(departure) {
            const statusColors = {
                pending: 'bg-yellow-100 text-yellow-800',
                ready: 'bg-green-100 text-green-800',
                confirmed: 'bg-blue-100 text-blue-800',
                cancelled: 'bg-red-100 text-red-800',
                draft: 'bg-gray-100 text-gray-800'
            };

            const statusTexts = {
                pending: 'Đang chuẩn bị',
                ready: 'Sẵn sàng',
                confirmed: 'Đã xác nhận',
                cancelled: 'Đã hủy',
                draft: 'Nháp'
            };

            document.getElementById('current-info').innerHTML = `
                <div class="text-center p-4 border border-gray-200 rounded-lg">
                    <div class="text-2xl font-bold text-gray-800 mb-1">ID: ${departure.id}</div>
                    <div class="text-sm text-gray-600">Departure ID</div>
                </div>
                
                <div class="text-center p-4 border border-gray-200 rounded-lg">
                    <div class="text-lg font-semibold text-gray-800 mb-1">${new Date(departure.departure_date).toLocaleDateString('vi-VN')}</div>
                    <div class="text-sm text-gray-600">Ngày khởi hành</div>
                </div>
                
                <div class="text-center p-4 border border-gray-200 rounded-lg">
                    <div class="inline-block px-3 py-1 rounded-full text-sm font-medium ${statusColors[departure.preparation_status] || 'bg-gray-100 text-gray-800'}">
                        ${statusTexts[departure.preparation_status] || 'Không xác định'}
                    </div>
                    <div class="text-sm text-gray-600 mt-1">Trạng thái hiện tại</div>
                </div>
            `;
        }

        async function updateStatus(newStatus) {
            const departureId = document.getElementById('departure-id').value;
            
            if (!departureId) {
                addTestResult('Vui lòng nhập Departure ID', 'error');
                return;
            }

            const statusTexts = {
                pending: 'Đang chuẩn bị',
                ready: 'Sẵn sàng',
                confirmed: 'Đã xác nhận',
                cancelled: 'Đã hủy',
                draft: 'Nháp'
            };

            try {
                addTestResult(`Đang cập nhật trạng thái thành "${statusTexts[newStatus]}"...`, 'info');
                
                const response = await fetch(`/api/departures/${departureId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        preparation_status: newStatus
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                    addTestResult(`✅ Đã cập nhật trạng thái thành "${statusTexts[newStatus]}"`, 'success', result.data);
                    
                    // Update display
                    currentDeparture = result.data;
                    displayDepartureInfo(result.data);
                } else {
                    addTestResult('❌ Lỗi: ' + result.message, 'error');
                }
            } catch (error) {
                addTestResult('❌ Lỗi kết nối: ' + error.message, 'error');
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            addTestResult('Trang test đã sẵn sàng', 'info');
            loadDepartureInfo();
        });
    </script>
</body>
</html>