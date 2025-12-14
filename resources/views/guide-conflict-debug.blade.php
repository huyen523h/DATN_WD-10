<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Debug Xung Đột HDV</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">
                <i class="fas fa-user-times mr-2 text-red-600"></i>
                Debug Xung Đột HDV
            </h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ngày kiểm tra</label>
                    <input type="date" id="check-date" class="w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                <div class="flex items-end">
                    <button onclick="checkGuideConflicts()" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        <i class="fas fa-search mr-2"></i>Kiểm tra xung đột
                    </button>
                </div>
            </div>
        </div>

        <!-- Results -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Available Guides -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">
                    <i class="fas fa-user-check mr-2 text-green-600"></i>
                    HDV Có Sẵn
                </h2>
                <div id="available-guides" class="space-y-3">
                    <p class="text-gray-600">Chưa kiểm tra...</p>
                </div>
            </div>

            <!-- Conflicted Guides -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">
                    <i class="fas fa-user-times mr-2 text-red-600"></i>
                    HDV Bị Xung Đột
                </h2>
                <div id="conflicted-guides" class="space-y-3">
                    <p class="text-gray-600">Chưa kiểm tra...</p>
                </div>
            </div>
        </div>

        <!-- Departures on Date -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-plane-departure mr-2 text-blue-600"></i>
                Tất cả Departures trong ngày
            </h2>
            <div id="departures-list" class="space-y-3">
                <p class="text-gray-600">Chưa kiểm tra...</p>
            </div>
        </div>

        <!-- Test Assignment -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-flask mr-2 text-purple-600"></i>
                Test Gán HDV
            </h2>
            
            <form id="test-assignment-form" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Departure ID</label>
                        <input type="number" id="test-departure-id" class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="VD: 42">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">HDV Chính ID</label>
                        <select id="test-guide-id" class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="">Chọn HDV chính</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">HDV Dự phòng ID</label>
                        <select id="test-backup-guide-id" class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="">Chọn HDV dự phòng</option>
                        </select>
                    </div>
                </div>
                
                <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-md hover:bg-purple-700">
                    <i class="fas fa-user-plus mr-2"></i>Test Gán HDV
                </button>
            </form>
            
            <div id="test-result" class="mt-4 p-4 rounded-lg hidden">
                <!-- Test result will be shown here -->
            </div>
        </div>

        <!-- Activity Log -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-list mr-2 text-gray-600"></i>
                Log Hoạt Động
            </h2>
            <div id="activity-log" class="bg-gray-900 text-green-400 rounded-lg p-4 min-h-32 max-h-64 overflow-y-auto font-mono text-sm">
                <p>[SYSTEM] Debug page loaded</p>
            </div>
        </div>
    </div>

    <script>
        let activityLog = [];

        function addLog(message, type = 'info') {
            const timestamp = new Date().toLocaleTimeString('vi-VN');
            const logEntry = `[${timestamp}] ${type.toUpperCase()}: ${message}`;
            activityLog.unshift(logEntry);
            
            const container = document.getElementById('activity-log');
            container.innerHTML = '<p>' + activityLog.slice(0, 30).join('</p><p>') + '</p>';
            container.scrollTop = 0;
        }

        async function checkGuideConflicts() {
            const checkDate = document.getElementById('check-date').value;
            
            if (!checkDate) {
                addLog('Vui lòng chọn ngày kiểm tra', 'error');
                return;
            }

            try {
                addLog(`Kiểm tra xung đột HDV cho ngày ${checkDate}...`);
                
                // Get available guides
                const response = await fetch(`/api/guides/available?date=${checkDate}`);
                const data = await response.json();
                
                if (data.success) {
                    const availableGuides = data.data.filter(guide => guide.is_available);
                    const conflictedGuides = data.data.filter(guide => !guide.is_available);
                    
                    displayAvailableGuides(availableGuides);
                    displayConflictedGuides(conflictedGuides);
                    
                    // Populate guide selects
                    populateGuideSelects(data.data);
                    
                    addLog(`Tìm thấy ${availableGuides.length} HDV có sẵn, ${conflictedGuides.length} HDV bị xung đột`, 'info');
                    
                    // Get all departures on this date
                    await getDeparturesOnDate(checkDate);
                } else {
                    addLog(`Lỗi API: ${data.message}`, 'error');
                }
            } catch (error) {
                addLog(`Lỗi kết nối: ${error.message}`, 'error');
            }
        }

        function displayAvailableGuides(guides) {
            const container = document.getElementById('available-guides');
            
            if (guides.length === 0) {
                container.innerHTML = '<p class="text-gray-600">Không có HDV nào có sẵn</p>';
                return;
            }
            
            container.innerHTML = guides.map(guide => `
                <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-green-800">${guide.name}</p>
                            <p class="text-sm text-green-600">ID: ${guide.id} | ${guide.email}</p>
                        </div>
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                </div>
            `).join('');
        }

        function displayConflictedGuides(guides) {
            const container = document.getElementById('conflicted-guides');
            
            if (guides.length === 0) {
                container.innerHTML = '<p class="text-gray-600">Không có HDV nào bị xung đột</p>';
                return;
            }
            
            container.innerHTML = guides.map(guide => `
                <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-red-800">${guide.name}</p>
                            <p class="text-sm text-red-600">ID: ${guide.id} | ${guide.email}</p>
                            ${guide.conflicts.map(conflict => `
                                <p class="text-xs text-red-500 mt-1">
                                    • Departure #${conflict.departure_id}: ${conflict.tour_title} (${conflict.role})
                                </p>
                            `).join('')}
                        </div>
                        <i class="fas fa-times-circle text-red-600"></i>
                    </div>
                </div>
            `).join('');
        }

        function populateGuideSelects(guides) {
            const mainSelect = document.getElementById('test-guide-id');
            const backupSelect = document.getElementById('test-backup-guide-id');
            
            // Clear existing options
            mainSelect.innerHTML = '<option value="">Chọn HDV chính</option>';
            backupSelect.innerHTML = '<option value="">Chọn HDV dự phòng</option>';
            
            guides.forEach(guide => {
                const option1 = new Option(`${guide.name} (ID: ${guide.id})${guide.is_available ? '' : ' - XỬ ĐỤNG'}`, guide.id);
                const option2 = new Option(`${guide.name} (ID: ${guide.id})${guide.is_available ? '' : ' - XỬ ĐỤNG'}`, guide.id);
                
                if (!guide.is_available) {
                    option1.style.color = '#ef4444';
                    option2.style.color = '#ef4444';
                }
                
                mainSelect.add(option1);
                backupSelect.add(option2);
            });
        }

        async function getDeparturesOnDate(date) {
            try {
                // This is a custom endpoint we'll need to create or simulate
                addLog(`Lấy danh sách departures cho ngày ${date}...`);
                
                // For now, we'll simulate this by checking known departures
                const knownDepartures = [42, 50, 51]; // Add more as needed
                const departuresData = [];
                
                for (const depId of knownDepartures) {
                    try {
                        const response = await fetch(`/api/departures/${depId}`);
                        const data = await response.json();
                        
                        if (data.success && data.data.departure_date === date) {
                            departuresData.push(data.data);
                        }
                    } catch (error) {
                        // Ignore errors for non-existent departures
                    }
                }
                
                displayDeparturesOnDate(departuresData);
                addLog(`Tìm thấy ${departuresData.length} departures trong ngày ${date}`);
            } catch (error) {
                addLog(`Lỗi khi lấy departures: ${error.message}`, 'error');
            }
        }

        function displayDeparturesOnDate(departures) {
            const container = document.getElementById('departures-list');
            
            if (departures.length === 0) {
                container.innerHTML = '<p class="text-gray-600">Không có departure nào trong ngày này</p>';
                return;
            }
            
            container.innerHTML = departures.map(dep => `
                <div class="p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-medium text-gray-800">Departure #${dep.id}</h4>
                        <span class="text-sm text-gray-500">${dep.departure_date} ${dep.departure_time || ''}</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">Tour ID: ${dep.tour_id}</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                        <div>
                            <strong>HDV Chính:</strong> 
                            ${dep.guide ? `${dep.guide.name} (ID: ${dep.guide.id})` : 'Chưa gán'}
                        </div>
                        <div>
                            <strong>HDV Dự phòng:</strong> 
                            ${dep.backup_guide ? `${dep.backup_guide.name} (ID: ${dep.backup_guide.id})` : 'Chưa gán'}
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Test assignment form
        document.getElementById('test-assignment-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const departureId = document.getElementById('test-departure-id').value;
            const guideId = document.getElementById('test-guide-id').value;
            const backupGuideId = document.getElementById('test-backup-guide-id').value;
            
            if (!departureId) {
                addLog('Vui lòng nhập Departure ID', 'error');
                return;
            }
            
            const updateData = {};
            if (guideId) updateData.guide_id = parseInt(guideId);
            if (backupGuideId) updateData.backup_guide_id = parseInt(backupGuideId);
            
            try {
                addLog(`Test gán HDV cho departure ${departureId}...`);
                
                const response = await fetch(`/api/departures/${departureId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(updateData)
                });
                
                const result = await response.json();
                
                const resultContainer = document.getElementById('test-result');
                resultContainer.classList.remove('hidden');
                
                if (result.success) {
                    resultContainer.className = 'mt-4 p-4 rounded-lg bg-green-50 border border-green-200';
                    resultContainer.innerHTML = `
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>
                            <span class="text-green-800 font-medium">Gán HDV thành công!</span>
                        </div>
                        <p class="text-sm text-green-700 mt-2">${result.message}</p>
                    `;
                    addLog('✅ Gán HDV thành công', 'success');
                } else {
                    resultContainer.className = 'mt-4 p-4 rounded-lg bg-red-50 border border-red-200';
                    resultContainer.innerHTML = `
                        <div class="flex items-center">
                            <i class="fas fa-times-circle text-red-600 mr-2"></i>
                            <span class="text-red-800 font-medium">Gán HDV thất bại!</span>
                        </div>
                        <p class="text-sm text-red-700 mt-2">${result.message}</p>
                    `;
                    addLog(`❌ Gán HDV thất bại: ${result.message}`, 'error');
                }
            } catch (error) {
                addLog(`❌ Lỗi khi gán HDV: ${error.message}`, 'error');
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Set default date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('check-date').value = today;
            
            addLog('Trang debug HDV đã sẵn sàng');
        });
    </script>
</body>
</html>