@extends('layouts.admin')
@section('title','Cấu hình') @section('pageTitle','Thông tin phòng khám')
@section('content')
<div class="row"><div class="col-xl-8"><div class="panel"><span class="eyebrow">Nhận diện hiển thị</span><h2 class="h5 fw-bold mt-2">Thông tin Phòng khám An Tâm</h2>
<form method="post" action="{{ route('admin.settings.update') }}" class="row g-3 mt-2" enctype="multipart/form-data">@csrf @method('PUT')
<div class="col-md-6"><label class="form-label">Tên phòng khám</label><input class="form-control" name="clinic_name" value="{{ old('clinic_name',$setting->clinic_name) }}"></div>
<div class="col-md-3"><label class="form-label">Điện thoại</label><input class="form-control" name="phone" value="{{ old('phone',$setting->phone) }}"></div><div class="col-md-3"><label class="form-label">Email</label><input class="form-control" name="email" value="{{ old('email',$setting->email) }}"></div>
<div class="col-12"><label class="form-label">Địa chỉ</label><input class="form-control" name="address" value="{{ old('address',$setting->address) }}"></div>
<div class="col-md-6"><label class="form-label">Giờ mở cửa</label><input class="form-control" type="time" name="opening_time" value="{{ substr($setting->opening_time,0,5) }}"></div><div class="col-md-6"><label class="form-label">Giờ đóng cửa</label><input class="form-control" type="time" name="closing_time" value="{{ substr($setting->closing_time,0,5) }}"></div>
<div class="col-12"><label class="form-label">Logo phòng khám</label><input class="form-control" type="file" name="logo" accept=".jpg,.jpeg,.png,.webp"><small class="text-muted">JPG, PNG hoặc WebP, tối đa 2MB.</small></div>
<div class="col-12"><label class="form-label">Chính sách khám</label><textarea class="form-control" rows="5" name="examination_policy">{{ old('examination_policy',$setting->examination_policy) }}</textarea></div><div><button class="btn btn-primary">Lưu cấu hình</button></div>
</form></div></div></div>
@endsection
