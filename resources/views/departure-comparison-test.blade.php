<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>So Sánh Các Departure Khác Nhau</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">
                <i class="fas fa-calendar-check mr-2 text-blue-600"></i>
                So Sánh Các Departure Khác Nhau
            </h1>
            
            <!-- Tour Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tour ID</label>
                    <input type="number" id="tour-id" value="14" class="w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                <div class="flex items-end">
                    <button onclick="loadTourDepartures()" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        <i class="fas fa-search mr-2"></i>Tải tất cả departures
                    </button>
                </div>
            </div>
        </div>

        <!-- Tour Info -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-info-circle mr-2 text-green-600"></i>
                Thông Tin Tour
            </h2>
            <div id="tour-info" class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-600">Chưa tải dữ liệu...</p>
            </div>
        </div>

        <!-- Departures List -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-plane-departure mr-2 text-purple-600"></i>
                Danh Sách Departures
            </h2>
            <div id="departures-list" class="space-y-4">
                <p class="text-gray-600">Chưa tải dữ liệu...</p>
            </div>
        </div>

        <!-- Schedule Comparison -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-calendar-alt mr-2 text-orange-600"></i>
                So Sánh Lịch Trình Theo Ngày
            </h2>
            <div id="schedule-comparison" class="overflow-x-auto">
                <p class="text-gray-600">Chọn departures để so sánh...</p>
            </div>
        </div>

        <!-- Test Results -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-clipboard-list mr-2 text-gray-600"></i>
                Kết Quả Phân Tích
            </h2>
            <div id="test-results" class="bg-gray-50 rounded-lg p-4 min-h-32">
                <p class="text-gray-600">Chưa có kết quả...</p>
            </div>
        </div>
    </div>

    <script>
        let tourData = null;
        let allDepartures = [];
        let selectedDepartures = [];
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
                container.innerHTML = '<p class="text-gray-600">Chưa có kết quả...</p>';
                return;
            }

            let html = '';
            testResults.slice(0, 10).forEach(result => {
                const statusClass = result.type === 'success' ? 'text-green-600' : 
                                  result.type === 'error' ? 'text-red-600' : 
                                  result.type === 'warning' ? 'text-yellow-600' : 'text-blue-600';
                const statusIcon = result.type === 'success' ? 'fa-check-circle' : 
                                 result.type === 'error' ? 'fa-times-circle' : 
                                 result.type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';
                
                html += `
                    <div class="flex items-start space-x-3 mb-3 p-2 bg-white rounded border-l-4 ${
                        result.type === 'success' ? 'border-green-500' : 
                        result.type === 'error' ? 'border-red-500' : 
                        result.type === 'warning' ? 'border-yellow-500' : 'border-blue-500'
                    }">
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

        async function loadTourDepartures() {
            const tourId = document.getElementById('tour-id').value;
            
            if (!tourId) {
                addTestResult('Vui lòng nhập Tour ID', 'error');
                return;
            }

            try {
                addTestResult('Đang tải tất cả departures của tour...', 'info');
                
                // Load tour departures
                const response = await fetch(`/api/tours/${tourId}/departures`);
                const data = await response.json();
                
                if (data.success) {
                    allDepartures = data.data;
                    displayTourInfo(tourId);
                    displayDeparturesList(data.data);
                    addTestResult(`✅ Đã tải ${data.data.length} departures`, 'success');
                    
                    if (data.data.length > 1) {
                        addTestResult(`📊 Tour có ${data.data.length} departures khác nhau`, 'info');
                    } else if (data.data.length === 1) {
                        addTestResult('ℹ️ Tour chỉ có 1 departure', 'info');
                    } else {
                        addTestResult('⚠️ Tour chưa có departure nào', 'warning');
                    }
                } else {
                    addTestResult('❌ Lỗi: ' + data.message, 'error');
                }
            } catch (error) {
                addTestResult('❌ Lỗi kết nối: ' + error.message, 'error');
            }
        }

        function displayTourInfo(tourId) {
            const container = document.getElementById('tour-info');
            
            container.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2">Tour ID</h4>
                        <p class="text-2xl font-bold text-blue-600">${tourId}</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2">Tổng Departures</h4>
                        <p class="text-2xl font-bold text-green-600">${allDepartures.length}</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2">Đã chọn</h4>
                        <p class="text-2xl font-bold text-purple-600">${selectedDepartures.length}</p>
                    </div>
                </div>
            `;
        }

        function displayDeparturesList(departures) {
            const container = document.getElementById('departures-list');
            
            if (!departures || departures.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-plane-departure text-3xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600">Không có departure nào</p>
                    </div>
                `;
                return;
            }

            let html = '';
            departures.forEach(departure => {
                const isSelected = selectedDepartures.includes(departure.id);
                const departureDate = new Date(departure.departure_date);
                const today = new Date();
                const daysDiff = Math.ceil((departureDate - today) / (1000 * 60 * 60 * 24));
                
                let statusText = '';
                let statusClass = '';
                if (daysDiff < 0) {
                    statusText = `Đã qua ${Math.abs(daysDiff)} ngày`;
                    statusClass = 'bg-red-100 text-red-800';
                } else if (daysDiff === 0) {
                    statusText = 'Hôm nay';
                    statusClass = 'bg-yellow-100 text-yellow-800';
                } else if (daysDiff <= 7) {
                    statusText = `Còn ${daysDiff} ngày`;
                    statusClass = 'bg-orange-100 text-orange-800';
                } else {
                    statusText = `Còn ${daysDiff} ngày`;
                    statusClass = 'bg-green-100 text-green-800';
                }

                html += `
                    <div class="border rounded-lg p-4 ${isSelected ? 'border-blue-500 bg-blue-50' : 'border-gray-200'} hover:shadow-md transition-all cursor-pointer" onclick="toggleDeparture(${departure.id})">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="font-semibold text-gray-800">Departure #${departure.id}</h4>
                                <p class="text-lg text-blue-600 font-medium">${departureDate.toLocaleDateString('vi-VN')}</p>
                                <p class="text-sm text-gray-600">${departureDate.toLocaleDateString('vi-VN', { weekday: 'long' })}</p>
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-1 rounded text-xs font-medium ${statusClass}">
                                    ${statusText}
                                </span>
                                ${isSelected ? '<div class="mt-2"><i class="fas fa-check-circle text-blue-600"></i> Đã chọn</div>' : ''}
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <p><strong>Giờ khởi hành:</strong> ${departure.departure_time || 'Chưa có'}</p>
                                <p><strong>Địa điểm:</strong> ${departure.departure_location || 'Chưa có'}</p>
                            </div>
                            <div>
                                <p><strong>HDV chính:</strong> ${departure.guide ? departure.guide.name : 'Chưa gán'}</p>
                                <p><strong>HDV dự phòng:</strong> ${departure.backup_guide ? departure.backup_guide.name : 'Chưa gán'}</p>
                            </div>
                            <div>
                                <p><strong>Chỗ ngồi:</strong> ${departure.seats_available}/${departure.seats_total}</p>
                                <p><strong>Trạng thái:</strong> <span class="px-1 py-0.5 rounded text-xs ${getStatusClass(departure.preparation_status)}">${getStatusText(departure.preparation_status)}</span></p>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }

        function toggleDeparture(departureId) {
            const index = selectedDepartures.indexOf(departureId);
            if (index > -1) {
                selectedDepartures.splice(index, 1);
                addTestResult(`Bỏ chọn departure #${departureId}`, 'info');
            } else {
                selectedDepartures.push(departureId);
                addTestResult(`Chọn departure #${departureId}`, 'info');
            }
            
            displayDeparturesList(allDepartures);
            displayTourInfo(document.getElementById('tour-id').value);
            
            if (selectedDepartures.length >= 2) {
                compareSelectedDepartures();
            } else {
                document.getElementById('schedule-comparison').innerHTML = '<p class="text-gray-600">Chọn ít nhất 2 departures để so sánh...</p>';
            }
        }

        async function compareSelectedDepartures() {
            if (selectedDepartures.length < 2) return;
            
            try {
                addTestResult(`Đang so sánh ${selectedDepartures.length} departures...`, 'info');
                
                const tourId = document.getElementById('tour-id').value;
                const comparisons = [];
                
                for (const departureId of selectedDepartures) {
                    const response = await fetch(`/api/tours/${tourId}/schedules?departure_id=${departureId}`);
                    const data = await response.json();
                    
                    if (data.success) {
                        comparisons.push({
                            departure: data.data.departure,
                            schedules: data.data.schedules
                        });
                    }
                }
                
                displayScheduleComparison(comparisons);
                addTestResult(`✅ Đã so sánh ${comparisons.length} departures`, 'success');
                
            } catch (error) {
                addTestResult('❌ Lỗi khi so sánh: ' + error.message, 'error');
            }
        }

        function displayScheduleComparison(comparisons) {
            const container = document.getElementById('schedule-comparison');
            
            if (!comparisons || comparisons.length === 0) {
                container.innerHTML = '<p class="text-gray-600">Không có dữ liệu để so sánh</p>';
                return;
            }

            // Get max number of days
            const maxDays = Math.max(...comparisons.map(c => c.schedules?.length || 0));
            
            let html = `
                <table class="min-w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="border border-gray-300 px-4 py-2 text-left">Ngày</th>
            `;
            
            comparisons.forEach(comparison => {
                const departureDate = new Date(comparison.departure.departure_date);
                html += `
                    <th class="border border-gray-300 px-4 py-2 text-center">
                        <div class="font-semibold">Departure #${comparison.departure.id}</div>
                        <div class="text-sm text-blue-600">${departureDate.toLocaleDateString('vi-VN')}</div>
                    </th>
                `;
            });
            
            html += '</tr></thead><tbody>';
            
            for (let day = 1; day <= maxDays; day++) {
                html += `<tr class="${day % 2 === 0 ? 'bg-gray-50' : 'bg-white'}">`;
                html += `<td class="border border-gray-300 px-4 py-2 font-medium">Ngày ${day}</td>`;
                
                comparisons.forEach(comparison => {
                    const schedule = comparison.schedules?.find(s => s.day_number === day);
                    const departureDate = new Date(comparison.departure.departure_date);
                    
                    if (schedule) {
                        // Calculate actual date
                        const actualDate = new Date(departureDate);
                        actualDate.setDate(actualDate.getDate() + (day - 1));
                        const actualDateStr = actualDate.toLocaleDateString('vi-VN');
                        const dayOfWeek = actualDate.toLocaleDateString('vi-VN', { weekday: 'short' });
                        
                        html += `
                            <td class="border border-gray-300 px-4 py-2">
                                <div class="font-medium text-gray-800">${schedule.title}</div>
                                <div class="text-sm text-blue-600">${actualDateStr} (${dayOfWeek})</div>
                                <div class="text-xs text-gray-600">${schedule.location || 'Chưa có địa điểm'}</div>
                            </td>
                        `;
                    } else {
                        html += '<td class="border border-gray-300 px-4 py-2 text-gray-400 text-center">Không có lịch trình</td>';
                    }
                });
                
                html += '</tr>';
            }
            
            html += '</tbody></table>';
            container.innerHTML = html;
        }

        function getStatusClass(status) {
            const classes = {
                pending: 'bg-yellow-100 text-yellow-800',
                ready: 'bg-green-100 text-green-800',
                confirmed: 'bg-blue-100 text-blue-800',
                cancelled: 'bg-red-100 text-red-800',
                draft: 'bg-gray-100 text-gray-800'
            };
            return classes[status] || 'bg-gray-100 text-gray-800';
        }

        function getStatusText(status) {
            const texts = {
                pending: 'Đang chuẩn bị',
                ready: 'Sẵn sàng',
                confirmed: 'Đã xác nhận',
                cancelled: 'Đã hủy',
                draft: 'Nháp'
            };
            return texts[status] || 'Không xác định';
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            addTestResult('Trang so sánh departures đã sẵn sàng', 'info');
            loadTourDepartures();
        });
    </script>
</body>
</html>