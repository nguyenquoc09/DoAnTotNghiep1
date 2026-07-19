# Cài đặt trên XAMPP

## Yêu cầu

- XAMPP 7.4.16, Apache 2.4, PHP 7.4.16 và MariaDB/MySQL.
- Composer 2 chạy được bằng PHP XAMPP.
- Các extension: openssl, pdo_mysql, mbstring, tokenizer, xml, ctype, json, fileinfo, gd và zip.

## Các bước

1. Đặt project tại `C:\xampp\htdocs\clinic-management`.
2. Bật Apache và MySQL trong XAMPP Control Panel.
3. Tạo database:
   `CREATE DATABASE clinic_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;`
4. Chạy `composer install` bằng PHP 7.4.
5. Sao chép `.env.example` thành `.env`, giữ `DB_DATABASE=clinic_management` và sửa tài khoản MySQL nếu cần.
6. Chạy `php artisan key:generate`.
7. Chạy `php artisan migrate --seed`.
8. Nếu dùng upload công khai, chạy `php artisan storage:link`.
9. Truy cập `http://localhost/clinic-management/public`.

## Production

Đặt `APP_ENV=production`, `APP_DEBUG=false`, dùng mật khẩu database riêng, HTTPS và đổi toàn bộ mật khẩu mẫu. Laravel 8/PHP 7.4 đã hết vòng đời hỗ trợ và Composer báo advisory; cần lập kế hoạch nâng lên PHP/Laravel còn được hỗ trợ trước khi dùng cho dữ liệu y tế thật.

## Lỗi thường gặp

- `php` không có trong PATH: dùng `C:\xampp\php\php.exe artisan ...`.
- `Unknown database`: tạo database trước khi chạy Artisan.
- `could not find driver`: bật `extension=pdo_mysql` trong `php.ini`.
- CSS/URL sai: kiểm tra `APP_URL=http://localhost/clinic-management/public`, sau đó `php artisan config:clear`.
- Ảnh không hiển thị: kiểm tra quyền ghi `storage`/`public/uploads` và đường dẫn Apache.
