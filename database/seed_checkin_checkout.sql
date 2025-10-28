-- Script SQL để seed dữ liệu check-in/check-out mẫu
-- Chạy script này trực tiếp trong MySQL để thêm 4 bản ghi mẫu

-- Lấy các user_id và booking_id từ database
-- Giả sử có ít nhất 2 user (id 3, 4) và ít nhất 2 booking (id 1, 2)
-- Thay đổi các giá trị này theo dữ liệu thực tế trong database của bạn

-- Kiểm tra và lấy user_id từ bảng users có role customer
SET @user1 = (SELECT id FROM users LIMIT 1 OFFSET 2); -- Lấy user thứ 3 (index 2)
SET @user2 = (SELECT id FROM users LIMIT 1 OFFSET 3); -- Lấy user thứ 4 (index 3)

-- Kiểm tra và lấy booking_id từ bảng bookings
SET @booking1 = (SELECT id FROM bookings WHERE status = 'confirmed' LIMIT 1);
SET @booking2 = (SELECT id FROM bookings WHERE status = 'confirmed' LIMIT 1 OFFSET 1);

-- Nếu không có user hoặc booking, tạo giá trị mặc định
SET @user1 = IFNULL(@user1, 3);
SET @user2 = IFNULL(@user2, 4);
SET @booking1 = IFNULL(@booking1, 1);
SET @booking2 = IFNULL(@booking2, 2);

-- Insert 4 bản ghi check-in/check-out mẫu
INSERT INTO check_in_outs 
    (user_id, booking_id, type, check_time, location, latitude, longitude, notes, status, verified_by, verified_at, metadata, created_at, updated_at) 
VALUES
    -- Check-in 1: Đã xác nhận (2 ngày trước)
    (
        @user1, 
        @booking1, 
        'check_in',
        DATE_SUB(NOW(), INTERVAL 2 DAY) + INTERVAL 7 HOUR + INTERVAL 30 MINUTE,
        'Bến xe Miền Tây, Q. Bình Tân, TP.HCM',
        10.7530,
        106.6274,
        'Khách hàng đến đúng giờ, ổn định',
        'confirmed',
        'Nhân viên Nguyễn Văn A',
        DATE_SUB(NOW(), INTERVAL 2 DAY) + INTERVAL 7 HOUR + INTERVAL 35 MINUTE,
        JSON_OBJECT('device', 'Mobile', 'ip_address', '192.168.1.100', 'photos', JSON_ARRAY('photo1.jpg', 'photo2.jpg')),
        NOW(),
        NOW()
    ),
    
    -- Check-out 1: Đã xác nhận (1 ngày trước)
    (
        @user2,
        @booking2,
        'check_out',
        DATE_SUB(NOW(), INTERVAL 1 DAY) + INTERVAL 18 HOUR + INTERVAL 45 MINUTE,
        'Khách sạn Pullman Saigon, Q. 1, TP.HCM',
        10.7837,
        106.7018,
        'Tour kết thúc tốt đẹp, khách hàng hài lòng',
        'confirmed',
        'Hướng dẫn viên Trần Thị B',
        DATE_SUB(NOW(), INTERVAL 1 DAY) + INTERVAL 18 HOUR + INTERVAL 50 MINUTE,
        JSON_OBJECT('device', 'Tablet', 'ip_address', '192.168.1.101', 'feedback', 'Excellent service'),
        NOW(),
        NOW()
    ),
    
    -- Check-in 2: Đang chờ xác nhận (12 giờ trước)
    (
        @user1,
        @booking2,
        'check_in',
        DATE_SUB(NOW(), INTERVAL 12 HOUR),
        'Sân bay Nội Bài, Hà Nội',
        21.2210,
        105.8066,
        'Check-in tại sân bay',
        'pending',
        NULL,
        NULL,
        JSON_OBJECT('device', 'Mobile', 'ip_address', '10.0.0.50'),
        NOW(),
        NOW()
    ),
    
    -- Check-out 2: Đang chờ xác nhận (3 giờ trước)
    (
        @user2,
        @booking1,
        'check_out',
        DATE_SUB(NOW(), INTERVAL 3 HOUR),
        'Bãi biển Mỹ Khê, Đà Nẵng',
        16.0597,
        108.2456,
        'Check-out tại điểm cuối tour',
        'pending',
        NULL,
        NULL,
        JSON_OBJECT('device', 'Mobile', 'ip_address', '172.16.0.10', 'weather', 'Sunny, 28°C'),
        NOW(),
        NOW()
    );

-- Hiển thị kết quả
SELECT 'Đã tạo thành công 4 bản ghi check-in/check-out mẫu!' as message;
SELECT COUNT(*) as total_records FROM check_in_outs;

