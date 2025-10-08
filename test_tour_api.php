<?php

echo "=== TEST TOUR API ENDPOINTS ===\n\n";

$baseUrl = 'http://127.0.0.1:8000/api';

// Test 1: Get all tours
echo "1. Testing GET /api/tours...\n";
$response = @file_get_contents($baseUrl . '/tours');
if ($response) {
    $data = json_decode($response, true);
    if ($data && isset($data['success']) && $data['success']) {
        echo "✅ Tours API working - Found " . count($data['data']['data']) . " tours\n";
        echo "   - Total: " . $data['data']['total'] . " tours\n";
        echo "   - Per page: " . $data['data']['per_page'] . "\n";
        echo "   - Current page: " . $data['data']['current_page'] . "\n";
    } else {
        echo "❌ API Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
    }
} else {
    echo "❌ Failed to get tours response\n";
}

// Test 2: Get featured tours
echo "\n2. Testing GET /api/tours/featured...\n";
$response = @file_get_contents($baseUrl . '/tours/featured');
if ($response) {
    $data = json_decode($response, true);
    if ($data && isset($data['success']) && $data['success']) {
        echo "✅ Featured tours API working - Found " . count($data['data']) . " featured tours\n";
    } else {
        echo "❌ Featured tours API error\n";
    }
} else {
    echo "❌ Failed to get featured tours response\n";
}

// Test 3: Get tours by location
echo "\n3. Testing GET /api/tours/location/hanoi...\n";
$response = @file_get_contents($baseUrl . '/tours/location/hanoi');
if ($response) {
    $data = json_decode($response, true);
    if ($data && isset($data['success']) && $data['success']) {
        echo "✅ Location tours API working - Found " . count($data['data']) . " tours in Hanoi\n";
    } else {
        echo "❌ Location tours API error\n";
    }
} else {
    echo "❌ Failed to get location tours response\n";
}

// Test 4: Get single tour
echo "\n4. Testing GET /api/tours/1...\n";
$response = @file_get_contents($baseUrl . '/tours/1');
if ($response) {
    $data = json_decode($response, true);
    if ($data && isset($data['success']) && $data['success']) {
        echo "✅ Single tour API working\n";
        echo "   - Tour: " . $data['data']['title'] . "\n";
        echo "   - Price: " . $data['data']['formatted_price'] . "\n";
        echo "   - Location: " . $data['data']['location'] . "\n";
    } else {
        echo "❌ Single tour API error\n";
    }
} else {
    echo "❌ Failed to get single tour response\n";
}

echo "\n=== TOUR API TEST COMPLETED ===\n";
