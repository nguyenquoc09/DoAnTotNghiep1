@extends('layouts.app')
@section('roleTitle','Không gian bác sĩ')
@section('sidebar')<a href="{{ route('doctor.dashboard') }}"><i class="bi bi-grid-1x2"></i>Hôm nay</a><span>Khám bệnh</span><a href="{{ route('doctor.dashboard') }}#queue"><i class="bi bi-person-lines-fill"></i>Hàng đợi</a><a href="{{ route('profile.edit') }}"><i class="bi bi-person-circle"></i>Hồ sơ cá nhân</a>@endsection
