-- Script SQL để seed dữ liệu mẫu cho Tour365
-- Chạy script này trực tiếp trong MySQL

-- Thêm cột updated_at vào các bảng nếu chưa có
ALTER TABLE tour_images ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP NULL AFTER created_at;
ALTER TABLE tour_departures ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP NULL AFTER created_at;
ALTER TABLE promotions ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP NULL AFTER created_at;
ALTER TABLE reviews ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP NULL AFTER created_at;
ALTER TABLE chats ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP NULL AFTER created_at;
ALTER TABLE user_history ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP NULL AFTER created_at;
ALTER TABLE notifications ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP NULL AFTER created_at;
ALTER TABLE wishlists ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP NULL AFTER created_at;

-- Insert roles
INSERT IGNORE INTO roles (name, description, created_at, updated_at) VALUES
('admin', 'Quản trị viên hệ thống', NOW(), NOW()),
('staff', 'Nhân viên công ty', NOW(), NOW()),
('customer', 'Khách hàng', NOW(), NOW());

-- Insert categories
INSERT IGNORE INTO categories (name, description, created_at, updated_at) VALUES
('Du lịch trong nước', 'Các tour du lịch trong nước Việt Nam', NOW(), NOW()),
('Du lịch nước ngoài', 'Các tour du lịch quốc tế', NOW(), NOW()),
('Du lịch biển đảo', 'Các tour du lịch biển và đảo', NOW(), NOW()),
('Du lịch văn hóa', 'Các tour du lịch văn hóa và lịch sử', NOW(), NOW()),
('Du lịch thiên nhiên', 'Các tour du lịch khám phá thiên nhiên', NOW(), NOW()),
('Du lịch tâm linh', 'Các tour du lịch tâm linh và tôn giáo', NOW(), NOW());

-- Insert users
INSERT IGNORE INTO users (name, email, password, phone, address, created_at, updated_at) VALUES
('Admin Tour365', 'admin@tour365.vn', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0901234567', '123 Đường ABC, Quận 1, TP.HCM', NOW(), NOW()),
('Nhân viên Tour365', 'staff@tour365.vn', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0901234568', '456 Đường DEF, Quận 2, TP.HCM', NOW(), NOW()),
('Nguyễn Văn An', 'an.nguyen@email.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0901234569', '789 Đường GHI, Quận 3, TP.HCM', NOW(), NOW()),
('Trần Thị Bình', 'binh.tran@email.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0901234570', '321 Đường JKL, Quận 4, TP.HCM', NOW(), NOW()),
('Lê Văn Cường', 'cuong.le@email.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0901234571', '654 Đường MNO, Quận 5, TP.HCM', NOW(), NOW());

-- Insert user roles
INSERT IGNORE INTO user_roles (user_id, role_id, assigned_at, created_at, updated_at) VALUES
(1, 1, NOW(), NOW(), NOW()), -- admin user
(2, 2, NOW(), NOW(), NOW()), -- staff user
(3, 3, NOW(), NOW(), NOW()), -- customer 1
(4, 3, NOW(), NOW(), NOW()), -- customer 2
(5, 3, NOW(), NOW(), NOW()); -- customer 3

-- Insert tours
INSERT IGNORE INTO tours (category_id, title, short_description, description, price, location, duration, available_seats, created_at, updated_at) VALUES
(1, 'Đà Nẵng - Hội An 3N2Đ', 'Khám phá thành phố biển xinh đẹp và phố cổ Hội An', 'Tour du lịch Đà Nẵng - Hội An 3 ngày 2 đêm với lịch trình tham quan đầy đủ các điểm nổi tiếng như Bà Nà Hills, Cầu Vàng, phố cổ Hội An, chợ đêm Hội An...', 5500000, 'Đà Nẵng, Hội An', '3 ngày 2 đêm', 30, NOW(), NOW()),
(3, 'Phú Quốc 4N3Đ', 'Thiên đường biển đảo với bãi biển tuyệt đẹp', 'Tour Phú Quốc 4 ngày 3 đêm khám phá đảo ngọc với các hoạt động thú vị như lặn biển, câu cá, tham quan làng chài...', 7800000, 'Phú Quốc, Kiên Giang', '4 ngày 3 đêm', 25, NOW(), NOW()),
(1, 'Sapa 2N1Đ', 'Khám phá vùng núi Tây Bắc với ruộng bậc thang', 'Tour Sapa 2 ngày 1 đêm tham quan ruộng bậc thang, làng dân tộc, chợ tình Sapa...', 3200000, 'Sapa, Lào Cai', '2 ngày 1 đêm', 20, NOW(), NOW()),
(2, 'Thái Lan - Bangkok 5N4Đ', 'Khám phá xứ sở chùa vàng', 'Tour Thái Lan 5 ngày 4 đêm tham quan Bangkok, Pattaya với các điểm nổi tiếng như Cung điện Hoàng gia, chùa Wat Pho...', 12500000, 'Bangkok, Pattaya, Thái Lan', '5 ngày 4 đêm', 15, NOW(), NOW());

-- Insert tour images
INSERT IGNORE INTO tour_images (tour_id, image_url, is_cover, sort_order, created_at, updated_at) VALUES
(1, 'https://via.placeholder.com/800x600/4F46E5/FFFFFF?text=Da+Nang+Hoi+An', 1, 1, NOW(), NOW()),
(2, 'https://via.placeholder.com/800x600/059669/FFFFFF?text=Phu+Quoc', 1, 1, NOW(), NOW()),
(3, 'https://via.placeholder.com/800x600/DC2626/FFFFFF?text=Sapa', 1, 1, NOW(), NOW()),
(4, 'https://via.placeholder.com/800x600/7C3AED/FFFFFF?text=Bangkok+Thailand', 1, 1, NOW(), NOW());

-- Insert tour schedules
INSERT IGNORE INTO tour_schedules (tour_id, day_number, title, description, created_at, updated_at) VALUES
(1, 1, 'Ngày 1: Khởi hành và tham quan', 'Khởi hành từ TP.HCM, di chuyển đến Đà Nẵng, check-in khách sạn và tham quan các điểm nổi tiếng.', NOW(), NOW()),
(1, 2, 'Ngày 2: Tham quan chính', 'Tham quan Bà Nà Hills, Cầu Vàng, thưởng thức ẩm thực địa phương.', NOW(), NOW()),
(1, 3, 'Ngày 3: Hoàn thành tour', 'Tham quan phố cổ Hội An, mua sắm và trở về TP.HCM.', NOW(), NOW()),
(2, 1, 'Ngày 1: Khởi hành Phú Quốc', 'Khởi hành từ TP.HCM, di chuyển đến Phú Quốc, check-in resort.', NOW(), NOW()),
(2, 2, 'Ngày 2: Khám phá biển đảo', 'Tham quan các bãi biển đẹp, lặn biển, câu cá.', NOW(), NOW()),
(2, 3, 'Ngày 3: Tham quan đảo', 'Tham quan các đảo nhỏ, làng chài truyền thống.', NOW(), NOW()),
(2, 4, 'Ngày 4: Hoàn thành tour', 'Tham quan chợ đêm, mua sắm và trở về TP.HCM.', NOW(), NOW());

-- Insert tour departures
INSERT IGNORE INTO tour_departures (tour_id, departure_date, seats_total, seats_available, created_at, updated_at) VALUES
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
(4, DATE_ADD(NOW(), INTERVAL 29 DAY), 15, 15, NOW(), NOW());
