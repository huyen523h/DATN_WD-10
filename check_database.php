<?php
// Script kiểm tra cấu trúc database
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=tour365', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Kết nối database thành công!\n\n";
    
    // Kiểm tra cấu trúc bảng tour_departures
    $stmt = $pdo->query("DESCRIBE tour_departures");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📋 Cấu trúc bảng tour_departures:\n";
    foreach ($columns as $column) {
        echo "- {$column['Field']} ({$column['Type']})\n";
    }
    
    // Kiểm tra xem có cột updated_at không
    $hasUpdatedAt = false;
    foreach ($columns as $column) {
        if ($column['Field'] === 'updated_at') {
            $hasUpdatedAt = true;
            break;
        }
    }
    
    if ($hasUpdatedAt) {
        echo "\n✅ Bảng tour_departures đã có cột updated_at\n";
    } else {
        echo "\n❌ Bảng tour_departures THIẾU cột updated_at\n";
        echo "🔧 Đang thêm cột updated_at...\n";
        
        $pdo->exec("ALTER TABLE tour_departures ADD COLUMN updated_at TIMESTAMP NULL AFTER created_at");
        echo "✅ Đã thêm cột updated_at vào bảng tour_departures\n";
    }
    
    // Kiểm tra các bảng khác
    $tables = ['tour_images', 'promotions', 'reviews', 'chats', 'user_history', 'notifications', 'wishlists'];
    
    foreach ($tables as $table) {
        $stmt = $pdo->query("DESCRIBE $table");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $hasUpdatedAt = false;
        foreach ($columns as $column) {
            if ($column['Field'] === 'updated_at') {
                $hasUpdatedAt = true;
                break;
            }
        }
        
        if (!$hasUpdatedAt) {
            echo "🔧 Đang thêm cột updated_at vào bảng $table...\n";
            $pdo->exec("ALTER TABLE $table ADD COLUMN updated_at TIMESTAMP NULL AFTER created_at");
            echo "✅ Đã thêm cột updated_at vào bảng $table\n";
        } else {
            echo "✅ Bảng $table đã có cột updated_at\n";
        }
    }
    
    echo "\n🎉 Tất cả bảng đã có cột updated_at!\n";
    
} catch (PDOException $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
