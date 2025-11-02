<?php
/**
 * Test file để kiểm tra chức năng Check-in/Check-out
 * Chạy file này để test các API endpoints
 */

// Test data
$baseUrl = 'http://localhost:8000'; // Thay đổi URL nếu cần
$testData = [
    'user_id' => 1,
    'booking_id' => 1,
    'type' => 'check_in',
    'check_time' => date('Y-m-d H:i:s'),
    'location' => 'Test Location',
    'latitude' => 10.8231,
    'longitude' => 106.6297,
    'notes' => 'Test check-in'
];

echo "=== TEST CHECK-IN/CHECK-OUT FUNCTIONALITY ===\n\n";

// Test 1: Tạo check-in mới
echo "1. Testing CREATE check-in...\n";
$createUrl = $baseUrl . '/admin/check-in-out';
$createData = json_encode($testData);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $createUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $createData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status Code: $httpCode\n";
echo "Response: $response\n\n";

// Test 2: Lấy danh sách check-in/out
echo "2. Testing INDEX (list all)...\n";
$indexUrl = $baseUrl . '/admin/check-in-out';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $indexUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: text/html'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status Code: $httpCode\n";
echo "Response Length: " . strlen($response) . " characters\n\n";

// Test 3: Lấy thống kê
echo "3. Testing STATISTICS...\n";
$statsUrl = $baseUrl . '/admin/check-in-out-statistics?period=today';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $statsUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status Code: $httpCode\n";
echo "Response: $response\n\n";

echo "=== TEST COMPLETED ===\n";
echo "Nếu tất cả status code là 200, chức năng hoạt động bình thường.\n";
echo "Nếu có lỗi 500, có thể do thiếu dữ liệu test (users, bookings).\n";
echo "Nếu có lỗi 404, kiểm tra routes và controller.\n";
?>
