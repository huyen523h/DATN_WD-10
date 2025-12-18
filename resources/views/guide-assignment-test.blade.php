<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Gán Hướng Dẫn Viên</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">
                <i class="fas fa-user-tie mr-2 text-blue-600"></i>
                Test Gán Hướng Dẫn Viên
            </h1>
            
            <!-- Current Status -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="font-semibold text-blue-800 mb-2">Departure ID</h3>
                    <p class="text-2xl font-bold text-blue-900" id="current-departure">42</p>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <h3 class="font-semibold text-green-800 mb-2">HDV Chính</h3>
                    <p class="text-lg font-bold text-green-900" id="main-guide">Đang tải...</p>
                </div>
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <h3 class="font-semibold text-purple-800 mb-2">HDV Dự phòng</h3>
                    <p class="text-lg font-bold text-purple-900" id="backup-guide">Đang tải...</p>
                </div>
            </div>

            <!-- Load Current Info -->
            <div class="mb-6">
                <button onclick="loadCurrentDeparture()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    <i class="fas fa-sync mr-2"></i>Tải thông tin hiện tại
                </button>
            </div>
        </div>

        <!-- Available Guides -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-users mr-2 text-green-600"></i>
                Danh sách Hướng Dẫn Viên
            </h2>
            <div id="guides-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-2xl text-gray-400 mb-2"></i>
                    <p class="text-gray-600">Đang tải danh sách HDV...</p>
                </div>
            </div>
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
        let availableGuides = [];
        let currentDepartureData = null;

        // Add test result
        function addTestResult(message, type = 'info', details = null) {
            const timestamp = new Date().toLocaleTimeString('vi-VN');
            const result = { message, type, details, timestamp };
            testResults.unshift(result);
            updateTestResultsDisplay();
        }

        // Update test results display
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

        // Load current departure info
        async function loadCurrentDeparture() {
            const departureId = document.getElementById('current-departure').textContent;
            
            try {
                addTestResult('Đang tải thông tin departure...', 'info');
                
                const response = await fetch(`/api/departures/${departureId}`);
                const data = await response.json();
                
                if (data.success) {
                    currentDepartureData = data.data;
                    
                    // Update display
                    document.getElementById('main-guide').textContent = 
                        data.data.guide ? data.data.guide.name : 'Chưa gán';
                    document.getElementById('backup-guide').textContent = 
                        data.data.backup_guide ? data.data.backup_guide.name : 'Chưa gán';
                    
                    addTestResult('✅ Đã tải thông tin departure thành công', 'success', data.data);
                } else {
                    addTestResult('❌ Lỗi tải departure: ' + data.message, 'error');
                }
            } catch (error) {
                addTestResult('❌ Lỗi kết nối: ' + error.message, 'error');
            }
        }

        // Load available guides
        async function loadAvailableGuides() {
            try {
                addTestResult('Đang tải danh sách HDV...', 'info');
                
                const response = await fetch('/api/guides/available');
                const data = await response.json();
                
                if (data.success) {
                    availableGuides = data.data;
                    displayGuides(data.data);
                    addTestResult(`✅ Đã tải ${data.data.length} hướng dẫn viên`, 'success');
                } else {
                    addTestResult('❌ Lỗi tải HDV: ' + data.message, 'error');
                }
            } catch (error) {
                addTestResult('❌ Lỗi kết nối: ' + error.message, 'error');
            }
        }

        // Display guides
        function displayGuides(guides) {
            const container = document.getElementById('guides-list');
            
            if (!guides || guides.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full text-center py-8">
                        <i class="fas fa-users text-3xl text-gray-400 mb-2"></i>
                        <p class="text-gray-600">Không có hướng dẫn viên nào</p>
                    </div>
                `;
                return;
            }

            let html = '';
            guides.forEach(guide => {
                const isMainGuide = currentDepartureData?.guide?.id === guide.id;
                const isBackupGuide = currentDepartureData?.backup_guide?.id === guide.id;
                
                html += `
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow ${isMainGuide ? 'bg-green-50 border-green-300' : isBackupGuide ? 'bg-purple-50 border-purple-300' : ''}">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="font-semibold text-gray-800">${guide.name}</h4>
                                <p class="text-sm text-gray-600">${guide.email}</p>
                                <p class="text-sm text-gray-600">${guide.phone || 'Chưa có SĐT'}</p>
                            </div>
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">
                                #${guide.id}
                            </span>
                        </div>
                        
                        ${isMainGuide ? '<div class="mb-2 text-xs bg-green-100 text-green-800 px-2 py-1 rounded">HDV Chính hiện tại</div>' : ''}
                        ${isBackupGuide ? '<div class="mb-2 text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded">HDV Dự phòng hiện tại</div>' : ''}
                        
                        <div class="flex space-x-2">
                            <button onclick="assignGuide(${guide.id}, 'main')" class="flex-1 text-xs bg-green-100 text-green-800 px-2 py-1 rounded hover:bg-green-200 transition-colors">
                                <i class="fas fa-user-tie mr-1"></i>Gán HDV chính
                            </button>
                            <button onclick="assignGuide(${guide.id}, 'backup')" class="flex-1 text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded hover:bg-purple-200 transition-colors">
                                <i class="fas fa-user-shield mr-1"></i>Gán HDV dự phòng
                            </button>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }

        // Assign guide
        async function assignGuide(guideId, type) {
            const departureId = document.getElementById('current-departure').textContent;
            const fieldName = type === 'main' ? 'guide_id' : 'backup_guide_id';
            const typeName = type === 'main' ? 'chính' : 'dự phòng';
            
            try {
                addTestResult(`Đang gán HDV ${typeName}...`, 'info');
                
                const response = await fetch(`/api/departures/${departureId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        [fieldName]: guideId
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                    addTestResult(`✅ Đã gán HDV ${typeName} thành công!`, 'success', result.data);
                    
                    // Reload data to update display
                    await loadCurrentDeparture();
                    displayGuides(availableGuides);
                } else {
                    addTestResult(`❌ Lỗi gán HDV: ${result.message}`, 'error');
                }
            } catch (error) {
                addTestResult(`❌ Lỗi kết nối: ${error.message}`, 'error');
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadCurrentDeparture();
            loadAvailableGuides();
        });
    </script>
</body>
</html>