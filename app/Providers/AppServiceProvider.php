<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Tambahkan baris ini untuk memaksa HTTPS saat pakai Ngrok
        if (str_contains(request()->header('host'), 'ngrok-free.dev')) {
            \URL::forceScheme('https');
        }

        // 2. Kode Anda yang sudah ada
        $globalSetting = Setting::first();
        View::share('globalSetting', $globalSetting);
    }
}
