# Phòng khám An Tâm

Website quản lý phòng khám viết bằng Laravel 8, PHP 7.4 và MySQL/MariaDB. Hệ thống hỗ trợ bốn vai trò: quản trị viên, lễ tân, bác sĩ và bệnh nhân.

## Khởi động nhanh trên XAMPP

```powershell
C:\xampp\php\php.exe artisan migrate:fresh --seed
C:\xampp\php\php.exe artisan serve
```

Hoặc truy cập trực tiếp qua Apache: `http://localhost/clinic-management/public`.

| Vai trò | Email | Mật khẩu |
|---|---|---|
| Quản trị viên | `admin@clinic.local` | `Password123!` |
| Lễ tân | `receptionist@clinic.local` | `Password123!` |
| Bác sĩ | `doctor@clinic.local` | `Password123!` |
| Bệnh nhân | `patient@clinic.local` | `Password123!` |

Xem hướng dẫn đầy đủ tại [docs/INSTALLATION.md](docs/INSTALLATION.md).
