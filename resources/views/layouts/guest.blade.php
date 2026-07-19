<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Phòng khám An Tâm')</title>
    <meta name="description" content="Đặt lịch khám thuận tiện, chăm sóc tận tâm tại Phòng khám An Tâm.">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">@stack('styles')
</head>
<body>
<header class="guest-header sticky-top"><nav class="navbar navbar-expand-lg"><div class="container">
    <a class="brand" href="{{ route('home') }}"><span class="brand-mark"><i class="bi bi-heart-pulse-fill"></i></span><span><strong>{{ optional($clinicSetting ?? null)->clinic_name ?: 'Phòng khám An Tâm' }}</strong><small>Chăm sóc bằng sự thấu hiểu</small></span></a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-label="Mở menu"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="mainNav"><ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2"><li><a class="nav-link" href="{{ route('specialties.index') }}">Chuyên khoa</a></li><li><a class="nav-link" href="{{ route('doctors.index') }}">Đội ngũ bác sĩ</a></li>
    @auth<li><a class="btn btn-soft" href="{{ route(auth()->user()->dashboardRoute()) }}">Trang quản lý</a></li>@else<li><a class="nav-link" href="{{ route('login') }}">Đăng nhập</a></li><li><a class="btn btn-primary" href="{{ route('register') }}">Đặt lịch ngay</a></li>@endauth</ul></div>
</div></nav></header>
<main>@include('components.flash') @yield('content')</main>
<footer class="site-footer"><div class="container"><div class="row g-4"><div class="col-lg-6"><div class="brand text-white"><span class="brand-mark"><i class="bi bi-heart-pulse-fill"></i></span><span><strong>Phòng khám An Tâm</strong><small>Đồng hành cùng sức khỏe gia đình Việt</small></span></div></div><div class="col-lg-3"><strong>Liên hệ</strong><p class="mt-2 mb-0">1900 8686<br>hello@antam.vn</p></div><div class="col-lg-3"><strong>Giờ làm việc</strong><p class="mt-2 mb-0">Thứ 2 – Chủ nhật<br>07:00 – 20:00</p></div></div></div></footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script><script src="{{ asset('assets/js/app.js') }}"></script>@stack('scripts')
</body></html>
