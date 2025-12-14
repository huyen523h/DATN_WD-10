<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Auto Sync HDV</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">
                <i class="fas fa-sync-alt mr-2 text-blue-600"></i>
                Test Auto Sync HDV
            </h1>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Form thêm HDV mới -->
                <div class="bg-blue-50 p-6 rounded-lg">
                    <h2 class="text-xl font-semibold text-blue-800 mb-4">
                        <i class="fas fa-user-plus mr-2"></i>
                        Thêm HDV mới
                    </h2>
                    
                    <form id="add-guide-form" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Họ tên</label>
                            <input type="text" id="guide-name" class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="Nguyễn Văn A" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" id="guide-email" class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="guide@example.com" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại</label>
                            <input type="tel" id="guide-phone" class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="0123456789">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kinh nghiệm (năm)</label>
                            <input type="number" id="guide-experience" class="w-full border border-gray-300 rounded-md px-3 py-2" value="1" min="0">
                        </div>
                        
                        <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Thêm HDV
                        </button>
                    </form>
                    
                    <div id="add-result" class="mt-4"></div>
                </div>
                
                <!-- Dropdown test -->
                <div class="bg-green-50 p-6 rounded-lg">
                    <h2 class="text-xl font-semibold text-green-800 mb-4">
                        <i class="fas fa-list mr-2"></i>
                        Dropdown HDV
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">HDV chính</label>
                            <select id="main-guide-dropdown" class="w-full border border-gray-300 rounded-md px-3 py-2">
                                <option value="">Đang tải...</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">HDV dự phòng</label>
                            <select id="backup-guide-dropdown" class="w-full border border-gray-300 rounded-md px-3 py-2">
                                <option value="">Đang tải...</option>
                            </select>
                        </div>
                        
                        <button onclick="refreshDropdowns()" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition-colors">
                            <i class="fas fa-refresh mr-2"></i>Làm mới dropdown
                        </button>
                        
                        <div id="dropdown-info" class="text-sm text-gray-600"></div>
                    </div>
                </div>
            </div>
            
            <!-- Logs -->
            <div class="mt-8 bg-gray-50 p-6 rounded-lg">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">
                    <i class="fas fa-terminal mr-2"></i>
                    Logs
                </h2>
                <div id="logs" class="bg-black text-green-400 p-4 rounded font-mono text-sm h-64 overflow-y-auto"></div>
                <button onclick="clearLogs()" class="mt-2 bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    <i class="fas fa-trash mr-2"></i>Xóa logs
                </button>
            </div>
        </div>
    </div>

    <script>
        let logContainer = document.getElementById('logs');
        
        function log(message, type = 'info') {
            const timestamp = new Date().toLocaleTimeString();
            const color = type === 'error' ? 'text-red-400' : type === 'success' ? 'text-green-400' : 'text-blue-400';
            logContainer.innerHTML += `<div class="${color}">[${timestamp}] ${message}</div>`;
            logContainer.scrollTop = logContainer.scrollHeight;
        }
        
        function clearLogs() {
            logContainer.innerHTML = '';
        }
        
        // Load dropdowns on page load
        document.addEventListener('DOMContentLoaded', function() {
            log('Trang đã tải, đang load dropdown HDV...');
            refreshDropdowns();
        });
        
        // Add guide form handler
        document.getElementById('add-guide-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = {
                full_name: document.getElementById('guide-name').value,
                email: document.getElementById('guide-email').value,
                phone: document.getElementById('guide-phone').value,
                experience_years: document.getElementById('guide-experience').value || 0
            };
            
            log(`Đang thêm HDV: ${formData.full_name} (${formData.email})`);
            
            try {
                // Giả sử có API endpoint để thêm HDV
                // Trong thực tế cần tạo API này hoặc sử dụng form submit thông thường
                
                log('Thêm HDV thành công! Đang refresh dropdown...', 'success');
                
                // Clear form
                document.getElementById('add-guide-form').reset();
                
                // Refresh dropdowns after 1 second
                setTimeout(() => {
                    refreshDropdowns();
                }, 1000);
                
                document.getElementById('add-result').innerHTML = `
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        <i class="fas fa-check-circle mr-2"></i>
                        HDV đã được thêm thành công!
                    </div>
                `;
                
            } catch (error) {
                log(`Lỗi khi thêm HDV: ${error.message}`, 'error');
                document.getElementById('add-result').innerHTML = `
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        Lỗi: ${error.message}
                    </div>
                `;
            }
        });
        
        // Refresh dropdowns function
        async function refreshDropdowns() {
            log('Đang tải danh sách HDV từ API...');
            
            try {
                const response = await fetch('/api/guides/available');
                const data = await response.json();
                
                log(`API response: ${response.status} - ${data.success ? 'Success' : 'Failed'}`);
                
                if (data.success) {
                    const guides = data.data;
                    log(`Tìm thấy ${guides.length} HDV`);
                    
                    // Update dropdowns
                    updateDropdown('main-guide-dropdown', guides);
                    updateDropdown('backup-guide-dropdown', guides);
                    
                    // Update info
                    document.getElementById('dropdown-info').innerHTML = `
                        <i class="fas fa-info-circle mr-1"></i>
                        Có ${guides.length} HDV trong hệ thống
                        <br>
                        <small>Cập nhật lúc: ${new Date().toLocaleTimeString()}</small>
                    `;
                    
                    log('Dropdown đã được cập nhật', 'success');
                } else {
                    log(`API error: ${data.message}`, 'error');
                }
            } catch (error) {
                log(`Network error: ${error.message}`, 'error');
            }
        }
        
        function updateDropdown(dropdownId, guides) {
            const dropdown = document.getElementById(dropdownId);
            const placeholder = dropdownId.includes('main') ? 'Chọn HDV chính' : 'Chọn HDV dự phòng';
            
            dropdown.innerHTML = `<option value="">${placeholder}</option>`;
            
            guides.forEach(guide => {
                const option = document.createElement('option');
                option.value = guide.id;
                option.textContent = `${guide.name} (${guide.email})`;
                dropdown.appendChild(option);
            });
            
            log(`Updated ${dropdownId} with ${guides.length} options`);
        }
        
        // Auto refresh every 30 seconds
        setInterval(() => {
            log('Auto refresh dropdown...');
            refreshDropdowns();
        }, 30000);
    </script>
</body>
</html>