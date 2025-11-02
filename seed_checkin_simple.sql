-- Script SQL đơn giản để thêm 4 bản ghi check-in/check-out
-- Chạy script này trực tiếp trong MySQL hoặc phpMyAdmin

-- Thay đổi các giá trị user_id và booking_id theo dữ liệu thực tế trong database của bạn
-- Lấy các giá trị này từ các lệnh sau (chạy trước):

-- SELECT id, name, email FROM users WHERE id IN (SELECT user_id FROM user_roles WHERE role_id = (SELECT id FROM roles WHERE name = 'customer'));
-- SELECT id, user_id, tour_id, status FROM bookings WHERE status = 'confirmed';

-- Sau đó thay thế các giá trị trong script này

INSERT INTO check_in_outs 
    (user_id, booking_id, type, check_time, location, latitude, longitude, notes, status, verified_by, verified_at, metadata, created_at, updated_at) 
VALUES
    -- Check-in 1: Đã xác nhận (2 ngày trước)
    (
        3,  -- user_id - THAY ĐỔI theo data thực tế
        1,  -- booking_id - THAY ĐỔI theo data thực tế
        'check_in',
        DATE_SUB(NOW(), INTERVAL 2 DAY),
        'Bến xe Miền Tây, Q. Bình Tân, TP.HCM',
        10.7530,
        106.6274,
        'Khách hàng đến đúng giờ, ổn định',
        'confirmed',
        'Nhân viên Nguyễn Văn A',
        DATE_SUB(NOW(), INTERVAL 2 DAY),
        '{"device":"Mobile","ip_address":"192.168.1.100","photos":["photo1.jpg","photo2.jpg"]}',
        NOW(),
        NOW()
    ),
    
    -- Check-out 1: Đã xác nhận (1 ngày trước)
    (
        4,  -- user_id - THAY ĐỔI theo data thực tế
        1,  -- booking_id - THAY ĐỔI theo data thực tế
        'check_out',
        DATE_SUB(NOW(), INTERVAL 1 DAY),
        'Khách sạn Pullman Saigon, Q. 1, TP.HCM',
        10.7837,
        106.7018,
        'Tour kết thúc tốt đẹp, khách hàng hài lòng',
        'confirmed',
        'Hướng dẫn viên Trần Thị B',
        DATE_SUB(NOW(), INTERVAL 1 DAY),
        '{"device":"Tablet","ip_address":"192.168.1.101","feedback":"Excellent service"}',
        NOW(),
        NOW()
    ),
    
    -- Check-in 2: Chờ xác nhận (12 giờ trước)
    (
        3,  -- user_id - THAY ĐỔI theo data thực tế
        2,  -- booking_id - THAY ĐỔI theo data thực tế
        'check_in',
        DATE_SUB(NOW(), INTERVAL 12 HOUR),
        'Sân bay Nội Bài, Hà Nội',
        21.2210,
        105.8066,
        'Check-in tại sân bay',
        'pending',
        NULL,
        NULL,
        '{"device":"Mobile","ip_address":"10.0.0.50"}',
        NOW(),
        NOW()
    ),
    
    -- Check-out 2: Chờ xác nhận (3 giờ trước)
    (
        4,  -- user_id - THAY ĐỔI theo data thực tế
        2,  -- booking_id - THAY ĐỔI theo data thực tế
        'check_out',
        DATE_SUB(NOW(), INTERVAL 3 HOUR),
        'Bãi biển Mỹ Khê, Đà Nẵng',
        16.0597,
        108.2456,
        'Check-out tại điểm cuối tour',
        'pending',
        NULL,
        NULL,
        '{"device":"Mobile","ip_address":"172.16.0.10","weather":"Sunny, 28°C"}',
        NOW(),
        NOW()
    );

-- Sau khi chạy, kiểm tra kết quả:
SELECT * FROM check_in_outs ORDER BY check_time DESC;

