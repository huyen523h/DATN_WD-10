<?php
/**
 * Test file để kiểm tra tính đồng nhất của giao diện
 * Chạy file này để test xem UI có consistent không
 */

$baseUrl = 'http://localhost:8000'; // Thay đổi URL nếu cần

echo "=== TEST UI CONSISTENCY ===\n\n";

// Test 1: Kiểm tra CSS files
echo "1. Testing CSS Files...\n";
$cssFiles = [
    'public/css/admin.css',
    'public/css/admin-icons.css',
    'public/build/assets/app-B5V2nTBk.css'
];

foreach ($cssFiles as $file) {
    if (file_exists($file)) {
        echo "✅ $file tồn tại\n";
        $size = filesize($file);
        echo "   Size: " . number_format($size) . " bytes\n";
    } else {
        echo "❌ $file không tồn tại\n";
    }
}
echo "\n";

// Test 2: Kiểm tra Bootstrap Icons
echo "2. Testing Bootstrap Icons...\n";
$bootstrapIconsUrl = 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $bootstrapIconsUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "✅ Bootstrap Icons CDN accessible\n";
} else {
    echo "❌ Bootstrap Icons CDN not accessible (HTTP $httpCode)\n";
}
echo "\n";

// Test 3: Kiểm tra Font Awesome
echo "3. Testing Font Awesome...\n";
$fontAwesomeUrl = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $fontAwesomeUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "✅ Font Awesome CDN accessible\n";
} else {
    echo "❌ Font Awesome CDN not accessible (HTTP $httpCode)\n";
}
echo "\n";

// Test 4: Kiểm tra trang admin
echo "4. Testing Admin Page...\n";
$adminUrl = $baseUrl . '/admin/check-in-out';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $adminUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: text/html'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "✅ Admin page accessible\n";
    
    // Kiểm tra các elements quan trọng
    $checks = [
        'Bootstrap CSS' => strpos($response, 'bootstrap.min.css') !== false,
        'Bootstrap Icons' => strpos($response, 'bootstrap-icons') !== false,
        'Font Awesome' => strpos($response, 'font-awesome') !== false,
        'Admin CSS' => strpos($response, 'admin.css') !== false,
        'Admin Icons CSS' => strpos($response, 'admin-icons.css') !== false,
        'Vite Assets' => strpos($response, 'vite') !== false,
        'Form Controls' => strpos($response, 'form-control') !== false,
        'Form Selects' => strpos($response, 'form-select') !== false,
        'Bootstrap Icons in HTML' => strpos($response, 'bi bi-') !== false,
        'Font Awesome in HTML' => strpos($response, 'fas fa-') !== false
    ];
    
    foreach ($checks as $check => $result) {
        if ($result) {
            echo "   ✅ $check found\n";
        } else {
            echo "   ❌ $check not found\n";
        }
    }
} else {
    echo "❌ Admin page not accessible (HTTP $httpCode)\n";
}
echo "\n";

// Test 5: Kiểm tra manifest.json
echo "5. Testing Vite Manifest...\n";
$manifestPath = __DIR__ . '/public/build/manifest.json';
if (file_exists($manifestPath)) {
    echo "✅ Manifest file exists\n";
    $manifest = json_decode(file_get_contents($manifestPath), true);
    if ($manifest) {
        echo "✅ Manifest is valid JSON\n";
        if (isset($manifest['resources/css/app.css'])) {
            echo "✅ CSS asset: " . $manifest['resources/css/app.css'] . "\n";
        }
        if (isset($manifest['resources/js/app.js'])) {
            echo "✅ JS asset: " . $manifest['resources/js/app.js'] . "\n";
        }
    } else {
        echo "❌ Manifest is not valid JSON\n";
    }
} else {
    echo "❌ Manifest file not found\n";
}
echo "\n";

echo "=== TEST COMPLETED ===\n";
echo "Nếu tất cả đều ✅, giao diện sẽ đồng nhất.\n";
echo "Nếu có ❌, cần kiểm tra lại các bước cài đặt.\n";
echo "\n";
echo "Hướng dẫn tiếp theo:\n";
echo "1. Truy cập: $baseUrl/admin/check-in-out\n";
echo "2. Kiểm tra xem icons có hiển thị không\n";
echo "3. Kiểm tra xem form elements có được style đúng không\n";
echo "4. Kiểm tra xem buttons có consistent không\n";
?>
