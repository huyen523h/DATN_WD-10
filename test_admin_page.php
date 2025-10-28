<?php
/**
 * Test file để kiểm tra trang admin check-in/check-out
 * Chạy file này để test xem trang có load được không
 */

$baseUrl = 'http://localhost:8000'; // Thay đổi URL nếu cần

echo "=== TEST ADMIN CHECK-IN/CHECK-OUT PAGE ===\n\n";

// Test 1: Trang chủ admin
echo "1. Testing Admin Dashboard...\n";
$dashboardUrl = $baseUrl . '/admin';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $dashboardUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: text/html'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status Code: $httpCode\n";
if ($httpCode == 200) {
    echo "✅ Admin dashboard accessible\n";
} else {
    echo "❌ Admin dashboard not accessible\n";
}
echo "\n";

// Test 2: Trang check-in/check-out
echo "2. Testing Check-in/Check-out Page...\n";
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

echo "Status Code: $httpCode\n";
if ($httpCode == 200) {
    echo "✅ Check-in/Check-out page accessible\n";
    // Kiểm tra xem có lỗi Vite không
    if (strpos($response, 'ViteManifestNotFoundException') !== false) {
        echo "❌ ViteManifestNotFoundException still present\n";
    } else {
        echo "✅ No ViteManifestNotFoundException\n";
    }
} else {
    echo "❌ Check-in/Check-out page not accessible\n";
}
echo "\n";

// Test 3: Kiểm tra file manifest.json
echo "3. Testing Vite Manifest...\n";
$manifestUrl = $baseUrl . '/build/manifest.json';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $manifestUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status Code: $httpCode\n";
if ($httpCode == 200) {
    echo "✅ Vite manifest.json accessible\n";
    $manifest = json_decode($response, true);
    if (isset($manifest['resources/css/app.css']) && isset($manifest['resources/js/app.js'])) {
        echo "✅ CSS and JS assets found in manifest\n";
    } else {
        echo "❌ CSS or JS assets missing from manifest\n";
    }
} else {
    echo "❌ Vite manifest.json not accessible\n";
}
echo "\n";

echo "=== TEST COMPLETED ===\n";
echo "Nếu tất cả đều ✅, hệ thống đã hoạt động bình thường.\n";
echo "Nếu có ❌, kiểm tra lại cấu hình server và database.\n";
?>
