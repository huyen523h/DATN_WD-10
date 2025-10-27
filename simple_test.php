<?php
/**
 * Simple test để kiểm tra trang admin
 * Chạy file này trong browser để test
 */

// Test 1: Kiểm tra file manifest.json
echo "<h2>1. Kiểm tra Vite Manifest</h2>";
$manifestPath = __DIR__ . '/public/build/manifest.json';
if (file_exists($manifestPath)) {
    echo "✅ File manifest.json tồn tại<br>";
    $manifest = json_decode(file_get_contents($manifestPath), true);
    if ($manifest) {
        echo "✅ File manifest.json hợp lệ<br>";
        if (isset($manifest['resources/css/app.css'])) {
            echo "✅ CSS asset được tìm thấy<br>";
        } else {
            echo "❌ CSS asset không tìm thấy<br>";
        }
        if (isset($manifest['resources/js/app.js'])) {
            echo "✅ JS asset được tìm thấy<br>";
        } else {
            echo "❌ JS asset không tìm thấy<br>";
        }
    } else {
        echo "❌ File manifest.json không hợp lệ<br>";
    }
} else {
    echo "❌ File manifest.json không tồn tại<br>";
    echo "Cần chạy: npm run build<br>";
}

// Test 2: Kiểm tra CSS files
echo "<h2>2. Kiểm tra CSS Files</h2>";
$cssFiles = [
    'public/css/admin.css',
    'public/build/assets/app-B5V2nTBk.css'
];

foreach ($cssFiles as $file) {
    if (file_exists($file)) {
        echo "✅ $file tồn tại<br>";
    } else {
        echo "❌ $file không tồn tại<br>";
    }
}

// Test 3: Kiểm tra JS files
echo "<h2>3. Kiểm tra JS Files</h2>";
$jsFiles = [
    'public/build/assets/app-Bj43h_rG.js'
];

foreach ($jsFiles as $file) {
    if (file_exists($file)) {
        echo "✅ $file tồn tại<br>";
    } else {
        echo "❌ $file không tồn tại<br>";
    }
}

// Test 4: Kiểm tra layout file
echo "<h2>4. Kiểm tra Layout File</h2>";
$layoutFile = 'resources/views/admin/layout.blade.php';
if (file_exists($layoutFile)) {
    echo "✅ Layout file tồn tại<br>";
    $content = file_get_contents($layoutFile);
    if (strpos($content, '@vite') !== false) {
        echo "✅ Vite directive được tìm thấy<br>";
    } else {
        echo "❌ Vite directive không tìm thấy<br>";
    }
    if (strpos($content, 'css/admin.css') !== false) {
        echo "✅ Fallback CSS được tìm thấy<br>";
    } else {
        echo "❌ Fallback CSS không tìm thấy<br>";
    }
} else {
    echo "❌ Layout file không tồn tại<br>";
}

echo "<h2>5. Kết luận</h2>";
echo "Nếu tất cả đều ✅, trang admin sẽ hoạt động bình thường.<br>";
echo "Nếu có ❌, cần kiểm tra lại các bước cài đặt.<br>";

echo "<h2>6. Hướng dẫn tiếp theo</h2>";
echo "1. Truy cập: <a href='http://localhost:8000/admin/check-in-out'>http://localhost:8000/admin/check-in-out</a><br>";
echo "2. Nếu vẫn có lỗi, kiểm tra log Laravel<br>";
echo "3. Đảm bảo database đã được migrate<br>";
?>
