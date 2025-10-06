<?php
// Script chạy seeder bằng SQL trực tiếp
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=tour365', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🚀 Bắt đầu chạy seeder bằng SQL...\n\n";
    
    // Xóa dữ liệu cũ nếu có
    echo "1. Xóa dữ liệu cũ...\n";
    $pdo->exec("DELETE FROM tour_departures");
    $pdo->exec("DELETE FROM tour_schedules");
    $pdo->exec("DELETE FROM tour_images");
    $pdo->exec("DELETE FROM tours");
    $pdo->exec("DELETE FROM user_roles");
    $pdo->exec("DELETE FROM users WHERE id > 1");
    echo "✅ Đã xóa dữ liệu cũ\n\n";
    
    // Insert roles
    echo "2. Insert roles...\n";
    $pdo->exec("INSERT IGNORE INTO roles (name, description, created_at, updated_at) VALUES
        ('admin', 'Quản trị viên hệ thống', NOW(), NOW()),
        ('staff', 'Nhân viên công ty', NOW(), NOW()),
        ('customer', 'Khách hàng', NOW(), NOW())");
    echo "✅ Roles đã được insert\n\n";
    
    // Insert categories
    echo "3. Insert categories...\n";
    $pdo->exec("INSERT IGNORE INTO categories (name, description, image_url, status, created_at, updated_at) VALUES
        ('Du lịch trong nước', 'Các tour du lịch trong nước Việt Nam', 'https://via.placeholder.com/300x200/4F46E5/FFFFFF?text=Trong+Nuoc', 'active', NOW(), NOW()),
        ('Du lịch nước ngoài', 'Các tour du lịch quốc tế', 'https://via.placeholder.com/300x200/059669/FFFFFF?text=Nuoc+Ngoai', 'active', NOW(), NOW()),
        ('Du lịch biển đảo', 'Các tour du lịch biển đảo', 'https://via.placeholder.com/300x200/DC2626/FFFFFF?text=Bien+Dao', 'active', NOW(), NOW()),
        ('Du lịch văn hóa', 'Các tour du lịch văn hóa lịch sử', 'https://via.placeholder.com/300x200/7C3AED/FFFFFF?text=Van+Hoa', 'active', NOW(), NOW()),
        ('Du lịch thiên nhiên', 'Các tour du lịch thiên nhiên', 'https://via.placeholder.com/300x200/F59E0B/FFFFFF?text=Thien+Nhien', 'active', NOW(), NOW()),
        ('Du lịch mạo hiểm', 'Các tour du lịch mạo hiểm', 'https://via.placeholder.com/300x200/EF4444/FFFFFF?text=Mao+Hiem', 'active', NOW(), NOW())");
    echo "✅ Categories đã được insert\n\n";
    
    // Insert tours
    echo "4. Insert tours...\n";
    $pdo->exec("INSERT IGNORE INTO tours (title, description, duration_days, price, category_id, status, created_at, updated_at) VALUES
        ('Tour Đà Nẵng - Hội An 3N2Đ', 'Khám phá vẻ đẹp của Đà Nẵng và phố cổ Hội An với những trải nghiệm tuyệt vời', 3, 2500000, 1, 'active', NOW(), NOW()),
        ('Tour Phú Quốc 4N3Đ', 'Nghỉ dưỡng tại đảo ngọc Phú Quốc với những bãi biển tuyệt đẹp', 4, 3500000, 3, 'active', NOW(), NOW()),
        ('Tour Sapa 3N2Đ', 'Khám phá vùng núi Tây Bắc với ruộng bậc thang và văn hóa dân tộc', 3, 1800000, 5, 'active', NOW(), NOW()),
        ('Tour Bangkok - Thailand 5N4Đ', 'Du lịch Thái Lan khám phá thủ đô Bangkok và các điểm đến nổi tiếng', 5, 8500000, 2, 'active', NOW(), NOW())");
    echo "✅ Tours đã được insert\n\n";
    
    // Insert tour images
    echo "5. Insert tour images...\n";
    $pdo->exec("INSERT IGNORE INTO tour_images (tour_id, image_url, is_cover, sort_order, created_at, updated_at) VALUES
        (1, 'https://via.placeholder.com/800x600/4F46E5/FFFFFF?text=Da+Nang+Hoi+An', 1, 1, NOW(), NOW()),
        (2, 'https://via.placeholder.com/800x600/059669/FFFFFF?text=Phu+Quoc', 1, 1, NOW(), NOW()),
        (3, 'https://via.placeholder.com/800x600/DC2626/FFFFFF?text=Sapa', 1, 1, NOW(), NOW()),
        (4, 'https://via.placeholder.com/800x600/7C3AED/FFFFFF?text=Bangkok+Thailand', 1, 1, NOW(), NOW())");
    echo "✅ Tour images đã được insert\n\n";
    
    // Insert tour schedules
    echo "6. Insert tour schedules...\n";
    $pdo->exec("INSERT IGNORE INTO tour_schedules (tour_id, day_number, title, description, created_at, updated_at) VALUES
        (1, 1, 'Ngày 1: Khởi hành Đà Nẵng', 'Khởi hành từ TP.HCM, di chuyển đến Đà Nẵng, tham quan cầu Rồng.', NOW(), NOW()),
        (1, 2, 'Ngày 2: Khám phá Hội An', 'Tham quan phố cổ Hội An, chùa Cầu, hội quán Phúc Kiến.', NOW(), NOW()),
        (1, 3, 'Ngày 3: Hoàn thành tour', 'Tham quan Bà Nà Hills, mua sắm và trở về TP.HCM.', NOW(), NOW()),
        (2, 1, 'Ngày 1: Khởi hành Phú Quốc', 'Khởi hành từ TP.HCM, di chuyển đến Phú Quốc, check-in resort.', NOW(), NOW()),
        (2, 2, 'Ngày 2: Khám phá biển đảo', 'Tham quan các bãi biển đẹp, lặn biển, câu cá.', NOW(), NOW()),
        (2, 3, 'Ngày 3: Tham quan đảo', 'Tham quan các đảo nhỏ, làng chài truyền thống.', NOW(), NOW()),
        (2, 4, 'Ngày 4: Hoàn thành tour', 'Tham quan chợ đêm, mua sắm và trở về TP.HCM.', NOW(), NOW())");
    echo "✅ Tour schedules đã được insert\n\n";
    
    // Insert tour departures
    echo "7. Insert tour departures...\n";
    $pdo->exec("INSERT IGNORE INTO tour_departures (tour_id, departure_date, seats_total, seats_available, created_at, updated_at) VALUES
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
    echo "✅ Tour departures đã được insert\n\n";
    
    // Kiểm tra kết quả
    echo "📊 Kiểm tra kết quả:\n";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM roles");
    $roles = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "- Roles: $roles\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM categories");
    $categories = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "- Categories: $categories\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM tours");
    $tours = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "- Tours: $tours\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM tour_images");
    $images = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "- Tour Images: $images\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM tour_schedules");
    $schedules = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "- Tour Schedules: $schedules\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM tour_departures");
    $departures = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "- Tour Departures: $departures\n";
    
    echo "\n🎉 Seeder hoàn thành thành công!\n";
    
} catch (PDOException $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
