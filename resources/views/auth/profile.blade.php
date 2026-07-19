@extends('layouts.'.auth()->user()->role->code)
@section('title','Hồ sơ cá nhân')
@section('pageTitle','Hồ sơ cá nhân')
@section('content')
<div class="row g-4">
    <div class="col-lg-7"><div class="panel"><h2 class="h5 fw-bold mb-3">Thông tin liên hệ</h2>
        <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">@csrf @method('PUT')
            <div class="mb-3"><label class="form-label">Họ tên</label><input class="form-control" name="name" value="{{ old('name',$user->name) }}"></div>
            <div class="mb-3"><label class="form-label">Điện thoại</label><input class="form-control" name="phone" value="{{ old('phone',$user->phone) }}"></div>
            @if($user->patient)<div class="row g-3 mb-3"><div class="col-md-6"><label class="form-label">Ngày sinh</label><input class="form-control" type="date" name="date_of_birth" value="{{ old('date_of_birth',optional($user->patient->date_of_birth)->format('Y-m-d')) }}"></div><div class="col-md-6"><label class="form-label">Giới tính</label><select class="form-select" name="gender"><option value="female" @if($user->patient->gender==='female') selected @endif>Nữ</option><option value="male" @if($user->patient->gender==='male') selected @endif>Nam</option><option value="other" @if($user->patient->gender==='other') selected @endif>Khác</option></select></div><div class="col-12"><label class="form-label">Địa chỉ</label><input class="form-control" name="address" value="{{ old('address',$user->patient->address) }}"></div></div>@endif
            <div class="mb-3"><label class="form-label">Ảnh đại diện</label><input class="form-control" type="file" name="avatar" accept=".jpg,.jpeg,.png,.webp"><small class="text-muted">JPG, PNG hoặc WebP, tối đa 2MB.</small></div>
            <button class="btn btn-primary">Lưu thay đổi</button>
        </form>
    </div></div>
    <div class="col-lg-5"><div class="panel"><h2 class="h5 fw-bold mb-3">Đổi mật khẩu</h2>
        <form method="post" action="{{ route('profile.password') }}">@csrf @method('PUT')
            <input class="form-control mb-3" type="password" name="current_password" placeholder="Mật khẩu hiện tại"><input class="form-control mb-3" type="password" name="password" placeholder="Mật khẩu mới"><input class="form-control mb-3" type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu"><button class="btn btn-primary">Đổi mật khẩu</button>
        </form>
    </div></div>
</div>
@endsection
