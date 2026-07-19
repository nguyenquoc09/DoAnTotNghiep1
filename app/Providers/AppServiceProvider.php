<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\ClinicSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        try {
            if (Schema::hasTable('clinic_settings')) {
                View::share('clinicSetting', ClinicSetting::first());
            }
        } catch (\Throwable $exception) {
            // Cho phép Artisan khởi động trước khi database được tạo.
        }
    }
}
