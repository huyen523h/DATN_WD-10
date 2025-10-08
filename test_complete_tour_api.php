<?php

echo "=== TEST COMPLETE TOUR API ===\n\n";

$baseUrl = 'http://127.0.0.1:8000/api';

// Test 1: Get all tours with filters
echo "1. Testing GET /api/tours with filters...\n";
$url = $baseUrl . '/tours?location=hanoi&min_price=1000000&max_price=5000000&available=true&search=singapore&sort_by=price&sort_order=asc&per_page=5';
$response = @file_get_contents($url);
if ($response) {
    $data = json_decode($response, true);
    if ($data && isset($data['success']) && $data['success']) {
        echo "✅ Tours API with filters working\n";
        echo "   - Total: " . $data['data']['total'] . " tours\n";
        echo "   - Per page: " . $data['data']['per_page'] . "\n";
        echo "   - Current page: " . $data['data']['current_page'] . "\n";
        echo "   - Filters applied: " . json_encode($data['filters_applied']) . "\n";
    } else {
        echo "❌ Tours API with filters error\n";
    }
} else {
    echo "❌ Failed to get tours with filters\n";
}

// Test 2: Get all tours (basic)
echo "\n2. Testing GET /api/tours (basic)...\n";
$response = @file_get_contents($baseUrl . '/tours');
if ($response) {
    $data = json_decode($response, true);
    if ($data && isset($data['success']) && $data['success']) {
        echo "✅ Basic tours API working\n";
        echo "   - Found " . count($data['data']['data']) . " tours\n";
        echo "   - Total: " . $data['data']['total'] . " tours\n";
    } else {
        echo "❌ Basic tours API error\n";
    }
} else {
    echo "❌ Failed to get basic tours\n";
}

// Test 3: Get featured tours
echo "\n3. Testing GET /api/tours/featured...\n";
$response = @file_get_contents($baseUrl . '/tours/featured');
if ($response) {
    $data = json_decode($response, true);
    if ($data && isset($data['success']) && $data['success']) {
        echo "✅ Featured tours API working\n";
        echo "   - Found " . count($data['data']) . " featured tours\n";
    } else {
        echo "❌ Featured tours API error\n";
    }
} else {
    echo "❌ Failed to get featured tours\n";
}

// Test 4: Get tours by location
echo "\n4. Testing GET /api/tours/location/hanoi...\n";
$response = @file_get_contents($baseUrl . '/tours/location/hanoi');
if ($response) {
    $data = json_decode($response, true);
    if ($data && isset($data['success']) && $data['success']) {
        echo "✅ Location tours API working\n";
        echo "   - Found " . count($data['data']) . " tours in Hanoi\n";
    } else {
        echo "❌ Location tours API error\n";
    }
} else {
    echo "❌ Failed to get location tours\n";
}

// Test 5: Get single tour
echo "\n5. Testing GET /api/tours/1...\n";
$response = @file_get_contents($baseUrl . '/tours/1');
if ($response) {
    $data = json_decode($response, true);
    if ($data && isset($data['success']) && $data['success']) {
        echo "✅ Single tour API working\n";
        echo "   - Tour: " . $data['data']['title'] . "\n";
        echo "   - Price: " . $data['data']['formatted_price'] . "\n";
        echo "   - Location: " . $data['data']['location'] . "\n";
        echo "   - Services: " . json_encode($data['data']['services']) . "\n";
        echo "   - Itinerary: " . json_encode($data['data']['itinerary']) . "\n";
    } else {
        echo "❌ Single tour API error\n";
    }
} else {
    echo "❌ Failed to get single tour\n";
}

echo "\n=== COMPLETE TOUR API TEST FINISHED ===\n";
