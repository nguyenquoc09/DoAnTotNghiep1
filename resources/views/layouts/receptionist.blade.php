@extends('layouts.app')
@section('roleTitle','Quầy tiếp nhận')
@section('sidebar')<a href="{{ route('receptionist.dashboard') }}"><i class="bi bi-grid-1x2"></i>Tổng quan</a><span>Tiếp đón</span><a href="{{ route('receptionist.patients.index') }}"><i class="bi bi-people"></i>Bệnh nhân</a><a href="{{ route('receptionist.appointments.index') }}"><i class="bi bi-calendar-check"></i>Lịch hẹn</a><a href="{{ route('receptionist.invoices.index') }}"><i class="bi bi-receipt"></i>Hóa đơn</a>@endsection
