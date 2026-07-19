@extends('layouts.app')
@section('roleTitle','Quản trị hệ thống')
@section('sidebar')
<a href="{{ route('admin.dashboard') }}"><i class="bi bi-grid-1x2"></i>Tổng quan</a><span>Vận hành</span>
<a href="{{ route('admin.users.index') }}"><i class="bi bi-people"></i>Tài khoản & vai trò</a><a href="{{ route('admin.specialties.index') }}"><i class="bi bi-diagram-3"></i>Chuyên khoa</a><a href="{{ route('admin.doctors.index') }}"><i class="bi bi-person-badge"></i>Bác sĩ</a><a href="{{ route('admin.schedules.index') }}"><i class="bi bi-calendar3"></i>Lịch làm việc</a><a href="{{ route('admin.services.index') }}"><i class="bi bi-clipboard2-pulse"></i>Dịch vụ</a><a href="{{ route('admin.medicines.index') }}"><i class="bi bi-capsule"></i>Kho thuốc</a>
<a href="{{ route('receptionist.appointments.index') }}"><i class="bi bi-calendar-check"></i>Toàn bộ lịch hẹn</a><a href="{{ route('receptionist.invoices.index') }}"><i class="bi bi-receipt"></i>Toàn bộ hóa đơn</a><span>Phân tích</span><a href="{{ route('admin.reports.index') }}"><i class="bi bi-bar-chart"></i>Thống kê</a><a href="{{ route('admin.activity-logs.index') }}"><i class="bi bi-activity"></i>Nhật ký hoạt động</a><a href="{{ route('admin.settings.edit') }}"><i class="bi bi-gear"></i>Cấu hình</a>
@endsection
