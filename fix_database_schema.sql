-- Script SQL để sửa database schema hiện tại
-- Chạy script này trực tiếp trong MySQL để thêm cột updated_at

-- Tạo database nếu chưa có
CREATE DATABASE IF NOT EXISTS tour365;
USE tour365;

-- Thêm cột updated_at vào các bảng (bỏ qua lỗi nếu cột đã tồn tại)
ALTER TABLE tour_images ADD COLUMN updated_at TIMESTAMP NULL AFTER created_at;
ALTER TABLE tour_departures ADD COLUMN updated_at TIMESTAMP NULL AFTER created_at;
ALTER TABLE promotions ADD COLUMN updated_at TIMESTAMP NULL AFTER created_at;
ALTER TABLE reviews ADD COLUMN updated_at TIMESTAMP NULL AFTER created_at;
ALTER TABLE chats ADD COLUMN updated_at TIMESTAMP NULL AFTER created_at;
ALTER TABLE user_history ADD COLUMN updated_at TIMESTAMP NULL AFTER created_at;
ALTER TABLE notifications ADD COLUMN updated_at TIMESTAMP NULL AFTER created_at;
ALTER TABLE wishlists ADD COLUMN updated_at TIMESTAMP NULL AFTER created_at;

-- Cập nhật các bản ghi hiện tại để có updated_at
UPDATE tour_images SET updated_at = created_at WHERE updated_at IS NULL;
UPDATE tour_departures SET updated_at = created_at WHERE updated_at IS NULL;
UPDATE promotions SET updated_at = created_at WHERE updated_at IS NULL;
UPDATE reviews SET updated_at = created_at WHERE updated_at IS NULL;
UPDATE chats SET updated_at = created_at WHERE updated_at IS NULL;
UPDATE user_history SET updated_at = created_at WHERE updated_at IS NULL;
UPDATE notifications SET updated_at = created_at WHERE updated_at IS NULL;
UPDATE wishlists SET updated_at = created_at WHERE updated_at IS NULL;

-- Hiển thị thông báo thành công
SELECT 'Database schema đã được sửa thành công!' as message;
