# Route chính

| Method | URL | Route name | Quyền |
|---|---|---|---|
| GET | `/` | `home` | Public |
| GET/POST | `/dang-nhap` | `login`, `login.submit` | Guest |
| GET/POST | `/dang-ky` | `register`, `register.submit` | Guest |
| POST | `/dang-xuat` | `logout` | Auth |
| GET | `/admin/dashboard` | `admin.dashboard` | Admin |
| Resource | `/admin/users`, `/specialties`, `/doctors`, `/schedules`, `/services`, `/medicines` | `admin.*` | Admin |
| GET | `/admin/reports` | `admin.reports.index` | Admin |
| GET | `/receptionist/dashboard` | `receptionist.dashboard` | Lễ tân/Admin |
| Resource | `/receptionist/patients` | `receptionist.patients.*` | Lễ tân/Admin |
| POST | `/receptionist/appointments/{id}/confirm` | `receptionist.appointments.confirm` | Lễ tân/Admin |
| POST | `/receptionist/appointments/{id}/check-in` | `receptionist.appointments.check-in` | Lễ tân/Admin |
| POST | `/receptionist/invoices/{id}/pay` | `receptionist.invoices.pay` | Lễ tân/Admin |
| GET/POST | `/doctor/examinations/{ticket}` | `doctor.examinations.*` | Bác sĩ được giao |
| POST | `/doctor/records/{record}/prescription` | `doctor.prescriptions.store` | Bác sĩ được giao |
| GET/POST | `/patient/appointments` | `patient.appointments.*` | Bệnh nhân |
| GET | `/patient/history` | `patient.history.index` | Bệnh nhân |
| GET | `/patient/records/{record}` | `patient.records.show` | Chủ hồ sơ |

Danh sách đầy đủ và controller tương ứng có thể xuất bằng `php artisan route:list`.
