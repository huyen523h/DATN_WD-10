<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=tour365', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "📊 Kiểm tra dữ liệu trong database:\n\n";
    
    // Kiểm tra tours
    $stmt = $pdo->query("SELECT id, title FROM tours");
    $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Tours (" . count($tours) . "):\n";
    foreach ($tours as $tour) {
        echo "- ID: {$tour['id']}, Title: {$tour['title']}\n";
    }
    echo "\n";
    
    // Kiểm tra tour_images
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM tour_images");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "Tour Images: $count\n";
    
    if ($count == 0) {
        echo "🔧 Đang insert tour images...\n";
        $pdo->exec("INSERT INTO tour_images (tour_id, image_url, is_cover, sort_order, created_at, updated_at) VALUES
            (1, 'https://via.placeholder.com/800x600/4F46E5/FFFFFF?text=Da+Nang+Hoi+An', 1, 1, NOW(), NOW()),
            (2, 'https://via.placeholder.com/800x600/059669/FFFFFF?text=Phu+Quoc', 1, 1, NOW(), NOW()),
            (3, 'https://via.placeholder.com/800x600/DC2626/FFFFFF?text=Sapa', 1, 1, NOW(), NOW()),
            (4, 'https://via.placeholder.com/800x600/7C3AED/FFFFFF?text=Bangkok+Thailand', 1, 1, NOW(), NOW())");
        echo "✅ Tour images đã được insert\n";
    }
    
    // Kiểm tra tour_schedules
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM tour_schedules");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "Tour Schedules: $count\n";
    
    if ($count == 0) {
        echo "🔧 Đang insert tour schedules...\n";
        $pdo->exec("INSERT INTO tour_schedules (tour_id, day_number, title, description, created_at, updated_at) VALUES
            (1, 1, 'Ngày 1: Khởi hành Đà Nẵng', 'Khởi hành từ TP.HCM, di chuyển đến Đà Nẵng, tham quan cầu Rồng.', NOW(), NOW()),
            (1, 2, 'Ngày 2: Khám phá Hội An', 'Tham quan phố cổ Hội An, chùa Cầu, hội quán Phúc Kiến.', NOW(), NOW()),
            (1, 3, 'Ngày 3: Hoàn thành tour', 'Tham quan Bà Nà Hills, mua sắm và trở về TP.HCM.', NOW(), NOW()),
            (2, 1, 'Ngày 1: Khởi hành Phú Quốc', 'Khởi hành từ TP.HCM, di chuyển đến Phú Quốc, check-in resort.', NOW(), NOW()),
            (2, 2, 'Ngày 2: Khám phá biển đảo', 'Tham quan các bãi biển đẹp, lặn biển, câu cá.', NOW(), NOW()),
            (2, 3, 'Ngày 3: Tham quan đảo', 'Tham quan các đảo nhỏ, làng chài truyền thống.', NOW(), NOW()),
            (2, 4, 'Ngày 4: Hoàn thành tour', 'Tham quan chợ đêm, mua sắm và trở về TP.HCM.', NOW(), NOW())");
        echo "✅ Tour schedules đã được insert\n";
    }
    
    // Kiểm tra tour_departures
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM tour_departures");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "Tour Departures: $count\n";
    
    if ($count == 0) {
        echo "🔧 Đang insert tour departures...\n";
        $pdo->exec("INSERT INTO tour_departures (tour_id, departure_date, seats_total, seats_available, created_at, updated_at) VALUES
            (1, DATE_ADD(NOW(), INTERVAL 7 DAY), 30, 30, NOW(), NOW()),
            (1, DATE_ADD(NOW(), INTERVAL 14 DAY), 30, 30, NOW(), NOW()),
            (1, DATE_ADD(NOW(), INTERVAL 21 DAY), 30, 30, NOW(), NOW()),
            (2, DATE_ADD(NOW(), INTERVAL 10 DAY), 25, 25, NOW(), NOW()),
            (2, DATE_ADD(NOW(), INTERVAL 17 DAY), 25, 25, NOW(), NOW()),
            (2, DATE_ADD(NOW(), INTERVAL 24 DAY), 25, 25, NOW(), NOW()),
            (3, DATE_ADD(NOW(), INTERVAL 5 DAY), 20, 20, NOW(), NOW()),
            (3, DATE_ADD(NOW(), INTERVAL 12 DAY), 20, 20, NOW(), NOW()),
            (3, DATE_ADD(NOW(), INTERVAL 19 DAY), 20, 20, NOW(), NOW()),
            (4, DATE_ADD(NOW(), INTERVAL 15 DAY), 15, 15, NOW(), NOW()),
            (4, DATE_ADD(NOW(), INTERVAL 22 DAY), 15, 15, NOW(), NOW()),
            (4, DATE_ADD(NOW(), INTERVAL 29 DAY), 15, 15, NOW(), NOW())");
        echo "✅ Tour departures đã được insert\n";
    }
    
    echo "\n🎉 Tất cả dữ liệu đã sẵn sàng!\n";
    
} catch (PDOException $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
