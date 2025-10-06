-- Script SQL hoàn chỉnh để tạo database Tour365
-- Chạy script này trực tiếp trong MySQL

-- Tạo database
CREATE DATABASE IF NOT EXISTS tour365;
USE tour365;

-- Tạo bảng users (Laravel default)
CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

-- Tạo bảng roles
CREATE TABLE IF NOT EXISTS roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

-- Tạo bảng user_roles
CREATE TABLE IF NOT EXISTS user_roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    role_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_role (user_id, role_id)
);

-- Cập nhật bảng users (thêm phone, address)
ALTER TABLE users ADD COLUMN phone VARCHAR(20) NULL AFTER email;
ALTER TABLE users ADD COLUMN address TEXT NULL AFTER phone;

-- Tạo bảng categories
CREATE TABLE IF NOT EXISTS categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    image_url VARCHAR(1024) NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

-- Tạo bảng tours
CREATE TABLE IF NOT EXISTS tours (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    duration_days INT NOT NULL,
    price DECIMAL(12,2) NOT NULL,
    category_id BIGINT UNSIGNED,
    status ENUM('active', 'inactive', 'draft') DEFAULT 'draft',
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Tạo bảng tour_images
CREATE TABLE IF NOT EXISTS tour_images (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tour_id BIGINT UNSIGNED NOT NULL,
    image_url VARCHAR(1024) NOT NULL,
    is_cover BOOLEAN DEFAULT FALSE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE
);

-- Tạo bảng tour_schedules
CREATE TABLE IF NOT EXISTS tour_schedules (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tour_id BIGINT UNSIGNED NOT NULL,
    day_number INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE
);

-- Tạo bảng tour_departures
CREATE TABLE IF NOT EXISTS tour_departures (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tour_id BIGINT UNSIGNED NOT NULL,
    departure_date DATE NOT NULL,
    seats_total INT NOT NULL,
    seats_available INT NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE
);

-- Tạo bảng promotions
CREATE TABLE IF NOT EXISTS promotions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    discount_type ENUM('percentage', 'fixed') NOT NULL,
    discount_percent DECIMAL(5,2) NULL,
    discount_amount DECIMAL(12,2) NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status VARCHAR(20) DEFAULT 'active',
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

-- Tạo bảng bookings
CREATE TABLE IF NOT EXISTS bookings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    tour_id BIGINT UNSIGNED NOT NULL,
    departure_id BIGINT UNSIGNED NOT NULL,
    adults INT NOT NULL DEFAULT 1,
    children INT DEFAULT 0,
    infants INT DEFAULT 0,
    total_amount DECIMAL(12,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    promotion_code VARCHAR(50) NULL,
    note TEXT,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE,
    FOREIGN KEY (departure_id) REFERENCES tour_departures(id) ON DELETE CASCADE,
    FOREIGN KEY (promotion_code) REFERENCES promotions(code) ON DELETE SET NULL
);

-- Tạo bảng payments
CREATE TABLE IF NOT EXISTS payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id BIGINT UNSIGNED NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    payment_method ENUM('cash', 'bank_transfer', 'credit_card', 'momo', 'zalopay') NOT NULL,
    status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    transaction_id VARCHAR(255) NULL,
    payment_date TIMESTAMP NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
);

-- Tạo bảng invoices
CREATE TABLE IF NOT EXISTS invoices (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id BIGINT UNSIGNED NOT NULL,
    invoice_number VARCHAR(50) NOT NULL UNIQUE,
    total_amount DECIMAL(12,2) NOT NULL,
    status ENUM('draft', 'sent', 'paid', 'cancelled') DEFAULT 'draft',
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
);

-- Tạo bảng reviews
CREATE TABLE IF NOT EXISTS reviews (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tour_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    rating INT NOT NULL,
    comment TEXT,
    images TEXT,
    status VARCHAR(20) DEFAULT 'visible',
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tạo bảng chats
CREATE TABLE IF NOT EXISTS chats (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
);

-- Tạo bảng chat_messages
CREATE TABLE IF NOT EXISTS chat_messages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    chat_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    message TEXT NOT NULL,
    message_type ENUM('text', 'image', 'file') DEFAULT 'text',
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (chat_id) REFERENCES chats(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tạo bảng support_tickets
CREATE TABLE IF NOT EXISTS support_tickets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    subject VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('open', 'in_progress', 'resolved', 'closed') DEFAULT 'open',
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tạo bảng documents
CREATE TABLE IF NOT EXISTS documents (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id BIGINT UNSIGNED NOT NULL,
    document_type ENUM('contract', 'invoice', 'receipt', 'other') NOT NULL,
    file_path VARCHAR(1024) NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_size INT,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
);

-- Tạo bảng user_history
CREATE TABLE IF NOT EXISTS user_history (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    action VARCHAR(255) NOT NULL,
    details TEXT,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tạo bảng notifications
CREATE TABLE IF NOT EXISTS notifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255),
    message TEXT,
    type VARCHAR(20) DEFAULT 'system',
    status VARCHAR(20) DEFAULT 'unread',
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tạo bảng wishlists
CREATE TABLE IF NOT EXISTS wishlists (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    tour_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_tour (user_id, tour_id)
);

-- Insert dữ liệu mẫu
INSERT IGNORE INTO roles (name, description, created_at, updated_at) VALUES
('admin', 'Quản trị viên hệ thống', NOW(), NOW()),
('staff', 'Nhân viên công ty', NOW(), NOW()),
('customer', 'Khách hàng', NOW(), NOW());

INSERT IGNORE INTO categories (name, description, image_url, status, created_at, updated_at) VALUES
('Du lịch trong nước', 'Các tour du lịch trong nước Việt Nam', 'https://via.placeholder.com/300x200/4F46E5/FFFFFF?text=Trong+Nuoc', 'active', NOW(), NOW()),
('Du lịch nước ngoài', 'Các tour du lịch quốc tế', 'https://via.placeholder.com/300x200/059669/FFFFFF?text=Nuoc+Ngoai', 'active', NOW(), NOW()),
('Du lịch biển đảo', 'Các tour du lịch biển đảo', 'https://via.placeholder.com/300x200/DC2626/FFFFFF?text=Bien+Dao', 'active', NOW(), NOW()),
('Du lịch văn hóa', 'Các tour du lịch văn hóa lịch sử', 'https://via.placeholder.com/300x200/7C3AED/FFFFFF?text=Van+Hoa', 'active', NOW(), NOW()),
('Du lịch thiên nhiên', 'Các tour du lịch thiên nhiên', 'https://via.placeholder.com/300x200/F59E0B/FFFFFF?text=Thien+Nhien', 'active', NOW(), NOW()),
('Du lịch mạo hiểm', 'Các tour du lịch mạo hiểm', 'https://via.placeholder.com/300x200/EF4444/FFFFFF?text=Mao+Hiem', 'active', NOW(), NOW());

-- Tạo admin user
INSERT IGNORE INTO users (name, email, password, phone, address, email_verified_at, created_at, updated_at) VALUES
('Admin Tour365', 'admin@tour365.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0901234567', '123 Đường ABC, Quận 1, TP.HCM', NOW(), NOW(), NOW());

-- Gán role admin
INSERT IGNORE INTO user_roles (user_id, role_id, created_at, updated_at) VALUES
(1, 1, NOW(), NOW());

-- Insert tours
INSERT IGNORE INTO tours (title, description, duration_days, price, category_id, status, created_at, updated_at) VALUES
('Tour Đà Nẵng - Hội An 3N2Đ', 'Khám phá vẻ đẹp của Đà Nẵng và phố cổ Hội An với những trải nghiệm tuyệt vời', 3, 2500000, 1, 'active', NOW(), NOW()),
('Tour Phú Quốc 4N3Đ', 'Nghỉ dưỡng tại đảo ngọc Phú Quốc với những bãi biển tuyệt đẹp', 4, 3500000, 3, 'active', NOW(), NOW()),
('Tour Sapa 3N2Đ', 'Khám phá vùng núi Tây Bắc với ruộng bậc thang và văn hóa dân tộc', 3, 1800000, 5, 'active', NOW(), NOW()),
('Tour Bangkok - Thailand 5N4Đ', 'Du lịch Thái Lan khám phá thủ đô Bangkok và các điểm đến nổi tiếng', 5, 8500000, 2, 'active', NOW(), NOW());

-- Insert tour images
INSERT IGNORE INTO tour_images (tour_id, image_url, is_cover, sort_order, created_at, updated_at) VALUES
(1, 'https://via.placeholder.com/800x600/4F46E5/FFFFFF?text=Da+Nang+Hoi+An', 1, 1, NOW(), NOW()),
(2, 'https://via.placeholder.com/800x600/059669/FFFFFF?text=Phu+Quoc', 1, 1, NOW(), NOW()),
(3, 'https://via.placeholder.com/800x600/DC2626/FFFFFF?text=Sapa', 1, 1, NOW(), NOW()),
(4, 'https://via.placeholder.com/800x600/7C3AED/FFFFFF?text=Bangkok+Thailand', 1, 1, NOW(), NOW());

-- Insert tour schedules
INSERT IGNORE INTO tour_schedules (tour_id, day_number, title, description, created_at, updated_at) VALUES
(1, 1, 'Ngày 1: Khởi hành Đà Nẵng', 'Khởi hành từ TP.HCM, di chuyển đến Đà Nẵng, tham quan cầu Rồng.', NOW(), NOW()),
(1, 2, 'Ngày 2: Khám phá Hội An', 'Tham quan phố cổ Hội An, chùa Cầu, hội quán Phúc Kiến.', NOW(), NOW()),
(1, 3, 'Ngày 3: Hoàn thành tour', 'Tham quan Bà Nà Hills, mua sắm và trở về TP.HCM.', NOW(), NOW()),
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

SELECT 'Database Tour365 đã được tạo thành công với dữ liệu mẫu!' as message;
