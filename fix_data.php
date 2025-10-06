<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=tour365', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🔧 Sửa dữ liệu với ID chính xác...\n\n";
    
    // Lấy ID thực tế của tours
    $stmt = $pdo->query("SELECT id, title FROM tours ORDER BY id");
    $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Tours hiện có:\n";
    foreach ($tours as $tour) {
        echo "- ID: {$tour['id']}, Title: {$tour['title']}\n";
    }
    echo "\n";
    
    // Insert tour images với ID chính xác
    echo "1. Insert tour images...\n";
    $pdo->exec("INSERT INTO tour_images (tour_id, image_url, is_cover, sort_order, created_at, updated_at) VALUES
        ({$tours[0]['id']}, 'https://via.placeholder.com/800x600/4F46E5/FFFFFF?text=Da+Nang+Hoi+An', 1, 1, NOW(), NOW()),
        ({$tours[1]['id']}, 'https://via.placeholder.com/800x600/059669/FFFFFF?text=Phu+Quoc', 1, 1, NOW(), NOW()),
        ({$tours[2]['id']}, 'https://via.placeholder.com/800x600/DC2626/FFFFFF?text=Sapa', 1, 1, NOW(), NOW()),
        ({$tours[3]['id']}, 'https://via.placeholder.com/800x600/7C3AED/FFFFFF?text=Bangkok+Thailand', 1, 1, NOW(), NOW())");
    echo "✅ Tour images đã được insert\n\n";
    
    // Insert tour schedules với ID chính xác
    echo "2. Insert tour schedules...\n";
    $pdo->exec("INSERT INTO tour_schedules (tour_id, day_number, title, description, created_at, updated_at) VALUES
        ({$tours[0]['id']}, 1, 'Ngày 1: Khởi hành Đà Nẵng', 'Khởi hành từ TP.HCM, di chuyển đến Đà Nẵng, tham quan cầu Rồng.', NOW(), NOW()),
        ({$tours[0]['id']}, 2, 'Ngày 2: Khám phá Hội An', 'Tham quan phố cổ Hội An, chùa Cầu, hội quán Phúc Kiến.', NOW(), NOW()),
        ({$tours[0]['id']}, 3, 'Ngày 3: Hoàn thành tour', 'Tham quan Bà Nà Hills, mua sắm và trở về TP.HCM.', NOW(), NOW()),
        ({$tours[1]['id']}, 1, 'Ngày 1: Khởi hành Phú Quốc', 'Khởi hành từ TP.HCM, di chuyển đến Phú Quốc, check-in resort.', NOW(), NOW()),
        ({$tours[1]['id']}, 2, 'Ngày 2: Khám phá biển đảo', 'Tham quan các bãi biển đẹp, lặn biển, câu cá.', NOW(), NOW()),
        ({$tours[1]['id']}, 3, 'Ngày 3: Tham quan đảo', 'Tham quan các đảo nhỏ, làng chài truyền thống.', NOW(), NOW()),
        ({$tours[1]['id']}, 4, 'Ngày 4: Hoàn thành tour', 'Tham quan chợ đêm, mua sắm và trở về TP.HCM.', NOW(), NOW())");
    echo "✅ Tour schedules đã được insert\n\n";
    
    // Insert tour departures với ID chính xác
    echo "3. Insert tour departures...\n";
    $pdo->exec("INSERT INTO tour_departures (tour_id, departure_date, seats_total, seats_available, created_at, updated_at) VALUES
        ({$tours[0]['id']}, DATE_ADD(NOW(), INTERVAL 7 DAY), 30, 30, NOW(), NOW()),
        ({$tours[0]['id']}, DATE_ADD(NOW(), INTERVAL 14 DAY), 30, 30, NOW(), NOW()),
        ({$tours[0]['id']}, DATE_ADD(NOW(), INTERVAL 21 DAY), 30, 30, NOW(), NOW()),
        ({$tours[1]['id']}, DATE_ADD(NOW(), INTERVAL 10 DAY), 25, 25, NOW(), NOW()),
        ({$tours[1]['id']}, DATE_ADD(NOW(), INTERVAL 17 DAY), 25, 25, NOW(), NOW()),
        ({$tours[1]['id']}, DATE_ADD(NOW(), INTERVAL 24 DAY), 25, 25, NOW(), NOW()),
        ({$tours[2]['id']}, DATE_ADD(NOW(), INTERVAL 5 DAY), 20, 20, NOW(), NOW()),
        ({$tours[2]['id']}, DATE_ADD(NOW(), INTERVAL 12 DAY), 20, 20, NOW(), NOW()),
        ({$tours[2]['id']}, DATE_ADD(NOW(), INTERVAL 19 DAY), 20, 20, NOW(), NOW()),
        ({$tours[3]['id']}, DATE_ADD(NOW(), INTERVAL 15 DAY), 15, 15, NOW(), NOW()),
        ({$tours[3]['id']}, DATE_ADD(NOW(), INTERVAL 22 DAY), 15, 15, NOW(), NOW()),
        ({$tours[3]['id']}, DATE_ADD(NOW(), INTERVAL 29 DAY), 15, 15, NOW(), NOW())");
    echo "✅ Tour departures đã được insert\n\n";
    
    // Kiểm tra kết quả cuối cùng
    echo "📊 Kết quả cuối cùng:\n";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM tour_images");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "- Tour Images: $count\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM tour_schedules");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "- Tour Schedules: $count\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM tour_departures");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "- Tour Departures: $count\n";
    
    echo "\n🎉 Tất cả dữ liệu đã được sửa thành công!\n";
    
} catch (PDOException $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
