<?php
// Test script for Promotion API endpoints

$baseUrl = 'http://127.0.0.1:8000/api';

echo "🧪 Testing Promotion API Endpoints\n";
echo "================================\n\n";

// Test 1: Get all active promotions
echo "1. Testing GET /api/promotions (Get all active promotions)\n";
$response = file_get_contents($baseUrl . '/promotions');
$data = json_decode($response, true);
echo "Status: " . ($data['success'] ? '✅ Success' : '❌ Failed') . "\n";
echo "Count: " . count($data['data']) . " promotions\n";
echo "Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";

// Test 2: Get specific promotion by code
echo "2. Testing GET /api/promotions/WELCOME10 (Get promotion by code)\n";
$response = file_get_contents($baseUrl . '/promotions/WELCOME10');
$data = json_decode($response, true);
echo "Status: " . ($data['success'] ? '✅ Success' : '❌ Failed') . "\n";
if ($data['success']) {
    echo "Code: " . $data['data']['code'] . "\n";
    echo "Description: " . $data['data']['description'] . "\n";
    echo "Discount: " . ($data['data']['discount_percent'] ? $data['data']['discount_percent'] . '%' : $data['data']['discount_amount'] . ' VND') . "\n";
} else {
    echo "Error: " . $data['message'] . "\n";
}
echo "\n";

// Test 3: Validate promotion code
echo "3. Testing POST /api/promotions/validate (Validate promotion code)\n";
$postData = json_encode([
    'code' => 'WELCOME10',
    'amount' => 1000000
]);

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => $postData
    ]
]);

$response = file_get_contents($baseUrl . '/promotions/validate', false, $context);
$data = json_decode($response, true);
echo "Status: " . ($data['success'] ? '✅ Success' : '❌ Failed') . "\n";
if ($data['success']) {
    echo "Original Amount: " . number_format($data['data']['original_amount']) . " VND\n";
    echo "Discount Amount: " . number_format($data['data']['discount_amount']) . " VND\n";
    echo "Final Amount: " . number_format($data['data']['final_amount']) . " VND\n";
} else {
    echo "Error: " . $data['message'] . "\n";
}
echo "\n";

// Test 4: Test invalid promotion code
echo "4. Testing GET /api/promotions/INVALID (Test invalid promotion code)\n";
$response = file_get_contents($baseUrl . '/promotions/INVALID');
$data = json_decode($response, true);
echo "Status: " . ($data['success'] ? '✅ Success' : '❌ Failed (Expected)') . "\n";
echo "Message: " . $data['message'] . "\n\n";

echo "🎉 API Testing Complete!\n";
echo "========================\n";
echo "Available endpoints:\n";
echo "- GET /api/promotions - Get all active promotions\n";
echo "- GET /api/promotions/{code} - Get promotion by code\n";
echo "- POST /api/promotions/validate - Validate promotion code\n";
echo "- GET /api/admin/promotions - Get all promotions (admin)\n";
echo "- POST /api/admin/promotions - Create promotion (admin)\n";
echo "- PUT /api/admin/promotions/{id} - Update promotion (admin)\n";
echo "- DELETE /api/admin/promotions/{id} - Delete promotion (admin)\n";
?>
