# Kế hoạch dự án

## Mục tiêu

Xây dựng website quản lý xuyên suốt quy trình đăng ký bệnh nhân, đặt lịch, tiếp nhận, khám bệnh, kê đơn, lập hóa đơn, thanh toán và báo cáo.

## Phạm vi và tác nhân

- Admin: tài khoản, danh mục, lịch làm việc, cấu hình và báo cáo.
- Receptionist: bệnh nhân, lịch hẹn, tiếp nhận, hóa đơn và thanh toán.
- Doctor: hàng đợi, bệnh án, đơn thuốc và hoàn tất khám.
- Patient: hồ sơ cá nhân, đặt lịch, kết quả khám, đơn thuốc và hóa đơn.

## Tiến độ

- [x] Phase 0: Laravel 8, cấu trúc và tài liệu nền.
- [x] Phase 1: design system, layouts, trang công khai và trang lỗi.
- [x] Phase 2: migrations, models, relationships và seed data.
- [x] Phase 3: authentication, role middleware, profile và policy.
- [x] Phase 4: module quản trị danh mục và dashboard.
- [x] Phase 5: module bệnh nhân và đặt lịch 30 phút.
- [x] Phase 6: module lễ tân và tiếp nhận.
- [x] Phase 7: module bác sĩ, bệnh án và đơn thuốc.
- [x] Phase 8: hóa đơn, tồn kho và thanh toán.
- [x] Phase 9: KPI và biểu đồ Chart.js.
- [x] Phase 10: smoke test, tài liệu và kiểm tra migration/route.

## Nguyên tắc

PHP 7.4, Laravel 8.83.29, Blade/Bootstrap 5, transaction cho nghiệp vụ nhiều bước, không truy vấn trong Blade, eager loading cho danh sách và mọi quyền đều được kiểm tra phía server.
