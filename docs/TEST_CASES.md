# Test cases

| ID | Chức năng | Điều kiện trước | Bước/dữ liệu | Kết quả mong đợi |
|---|---|---|---|---|
| AUTH-01 | Đăng nhập đúng | Tài khoản active | Email mẫu + `Password123!` | Vào dashboard đúng role |
| AUTH-02 | Đăng nhập sai | Có tài khoản | Mật khẩu sai | Báo lỗi tiếng Việt |
| AUTH-03 | Tài khoản khóa | Status locked | Đăng nhập | Bị từ chối |
| AUTH-04 | Trái quyền | Đăng nhập patient | Mở `/admin/dashboard` | HTTP 403 |
| PAT-01 | Tạo bệnh nhân | Lễ tân | Thông tin hợp lệ | Sinh mã BN, lưu hồ sơ |
| APP-01 | Đặt lịch | Slot còn trống | Bác sĩ/ngày/giờ/lý do | Lịch pending, tăng sức chứa |
| APP-02 | Ngày quá khứ | Có lịch bác sĩ | Chọn ngày cũ | Validation error |
| APP-03 | Trùng slot | Slot đã có lịch active | Đặt cùng bác sĩ/giờ | Từ chối |
| APP-04 | Hủy lịch | Pending/confirmed | Nhập lý do | Cancelled, giảm sức chứa |
| REC-01 | Tiếp nhận | Pending/confirmed | Check-in | Tạo phiếu duy nhất, cấp STT |
| EXA-01 | Bắt đầu khám | Bác sĩ được giao | Start | Ticket/appointment in_progress |
| EXA-02 | Hoàn thành | Có chẩn đoán/kết luận | Complete | Record/ticket/appointment completed |
| PRE-01 | Kê đơn | Bác sĩ được giao | Thuốc + số lượng | Chụp giá hiện tại |
| PRE-02 | Vượt tồn | Tồn kho thấp | Số lượng lớn | Từ chối toàn transaction |
| INV-01 | Lập hóa đơn | Khám completed | Dịch vụ + thuốc | Tổng server-side, trừ tồn một lần |
| PAY-01 | Thanh toán một phần | Invoice unpaid | Amount < total | partially_paid |
| PAY-02 | Thanh toán đủ | Invoice còn dư | Amount = outstanding | paid, lưu paid_at |
| PRIV-01 | Dữ liệu bệnh nhân | Hai patient | Mở record người khác | HTTP 403 |
| REP-01 | Doanh thu | Có invoice paid | Mở báo cáo | Tổng khớp dữ liệu nguồn |

Kết quả tự động hiện tại: xem `php artisan test`; kiểm thử giao diện thủ công ở 1440px, 768px và 390px.
