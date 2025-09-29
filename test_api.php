<?php

// Test API Authentication
$baseUrl = 'http://localhost:8000/api';

// Test data
$testData = [
    'register' => [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'phone' => '0123456789',
        'role' => 'customer'
    ],
    'login' => [
        'email' => 'test@example.com',
        'password' => 'password123'
    ],
    'admin_login' => [
        'email' => 'admin@example.com',
        'password' => 'password123'
    ]
];

echo "=== API Authentication Test ===\n\n";

// Test 1: Register
echo "1. Testing Register API...\n";
$registerResponse = makeRequest($baseUrl . '/register', 'POST', $testData['register']);
echo "Status: " . $registerResponse['status'] . "\n";
echo "Response: " . $registerResponse['body'] . "\n\n";

// Test 2: Login
echo "2. Testing Login API...\n";
$loginResponse = makeRequest($baseUrl . '/login', 'POST', $testData['login']);
echo "Status: " . $loginResponse['status'] . "\n";
echo "Response: " . $loginResponse['body'] . "\n\n";

// Extract token if login successful
$loginData = json_decode($loginResponse['body'], true);
$token = $loginData['data']['token'] ?? null;

if ($token) {
    // Test 3: Get Profile
    echo "3. Testing Profile API...\n";
    $profileResponse = makeRequest($baseUrl . '/profile', 'GET', null, $token);
    echo "Status: " . $profileResponse['status'] . "\n";
    echo "Response: " . $profileResponse['body'] . "\n\n";
    
    // Test 4: Logout
    echo "4. Testing Logout API...\n";
    $logoutResponse = makeRequest($baseUrl . '/logout', 'POST', null, $token);
    echo "Status: " . $logoutResponse['status'] . "\n";
    echo "Response: " . $logoutResponse['body'] . "\n\n";
}

// Test 5: Admin Login
echo "5. Testing Admin Login API...\n";
$adminLoginResponse = makeRequest($baseUrl . '/login', 'POST', $testData['admin_login']);
echo "Status: " . $adminLoginResponse['status'] . "\n";
echo "Response: " . $adminLoginResponse['body'] . "\n\n";

// Extract admin token
$adminLoginData = json_decode($adminLoginResponse['body'], true);
$adminToken = $adminLoginData['data']['token'] ?? null;

if ($adminToken) {
    // Test 6: Get All Users (Admin)
    echo "6. Testing Get All Users API (Admin)...\n";
    $usersResponse = makeRequest($baseUrl . '/users', 'GET', null, $adminToken);
    echo "Status: " . $usersResponse['status'] . "\n";
    echo "Response: " . $usersResponse['body'] . "\n\n";
}

echo "=== Test Complete ===\n";

function makeRequest($url, $method = 'GET', $data = null, $token = null) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    
    $headers = ['Content-Type: application/json'];
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'status' => $httpCode,
        'body' => $response
    ];
}
