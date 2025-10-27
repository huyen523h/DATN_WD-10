<?php
/**
 * Test file để kiểm tra giao diện check-in/check-out
 * Chạy file này để test xem layout đã đúng chưa
 */

$baseUrl = 'http://localhost:8000'; // Thay đổi URL nếu cần

echo "=== TEST CHECK-IN/CHECK-OUT LAYOUT ===\n\n";

// Test 1: Kiểm tra trang check-in/check-out
echo "1. Testing Check-in/Check-out Page...\n";
$checkinUrl = $baseUrl . '/admin/check-in-out';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $checkinUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: text/html'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "✅ Check-in/Check-out page accessible\n";
    
    // Kiểm tra các elements quan trọng
    $checks = [
        'Layout admin' => strpos($response, 'layouts.admin') !== false,
        'Bootstrap CSS' => strpos($response, 'bootstrap.min.css') !== false,
        'Font Awesome' => strpos($response, 'font-awesome') !== false,
        'Bootstrap Icons' => strpos($response, 'bootstrap-icons') !== false,
        'Admin CSS' => strpos($response, 'admin.css') !== false,
        'Admin Icons CSS' => strpos($response, 'admin-icons.css') !== false,
        'Container fluid' => strpos($response, 'container-fluid') !== false,
        'Card elements' => strpos($response, 'card') !== false,
        'Form elements' => strpos($response, 'form-control') !== false,
        'Button elements' => strpos($response, 'btn') !== false,
        'Statistics cards' => strpos($response, 'border-left-primary') !== false,
        'Table elements' => strpos($response, 'table') !== false
    ];
    
    foreach ($checks as $check => $result) {
        if ($result) {
            echo "   ✅ $check found\n";
        } else {
            echo "   ❌ $check not found\n";
        }
    }
    
    // Kiểm tra xem có sử dụng layout đúng không
    if (strpos($response, 'layouts.admin') !== false) {
        echo "   ✅ Using correct layout (layouts.admin)\n";
    } else {
        echo "   ❌ Not using correct layout\n";
    }
    
} else {
    echo "❌ Check-in/Check-out page not accessible (HTTP $httpCode)\n";
}
echo "\n";

// Test 2: Kiểm tra các trang khác
$pages = [
    'create' => '/admin/check-in-out/create',
    'statistics' => '/admin/check-in-out-statistics-page'
];

foreach ($pages as $name => $url) {
    echo "2. Testing $name page...\n";
    $pageUrl = $baseUrl . $url;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $pageUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: text/html'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200) {
        echo "   ✅ $name page accessible\n";
        if (strpos($response, 'layouts.admin') !== false) {
            echo "   ✅ Using correct layout\n";
        } else {
            echo "   ❌ Not using correct layout\n";
        }
    } else {
        echo "   ❌ $name page not accessible (HTTP $httpCode)\n";
    }
    echo "\n";
}

// Test 3: Kiểm tra CSS files
echo "3. Testing CSS Files...\n";
$cssFiles = [
    'public/css/admin.css',
    'public/css/admin-modern.css',
    'public/css/dashboard-professional.css',
    'public/css/admin-tables.css',
    'public/css/admin-icons.css'
];

foreach ($cssFiles as $file) {
    if (file_exists($file)) {
        echo "✅ $file tồn tại\n";
    } else {
        echo "❌ $file không tồn tại\n";
    }
}
echo "\n";

echo "=== TEST COMPLETED ===\n";
echo "Nếu tất cả đều ✅, giao diện check-in/check-out đã sử dụng layout đúng.\n";
echo "Nếu có ❌, cần kiểm tra lại các bước cập nhật.\n";
echo "\n";
echo "Hướng dẫn tiếp theo:\n";
echo "1. Truy cập: $baseUrl/admin/check-in-out\n";
echo "2. Kiểm tra xem giao diện có giống với các trang admin khác không\n";
echo "3. Kiểm tra xem có sử dụng layout admin chính không\n";
?>
