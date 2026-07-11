<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        \Illuminate\Pagination\Paginator::useBootstrapFour();

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
                \Illuminate\Support\Facades\View::share('app_name', $settings['app_name'] ?? 'ITAM Enterprise');
                \Illuminate\Support\Facades\View::share('company_name', $settings['company_name'] ?? 'PT CBA Chemical Industry');
            } else {
                \Illuminate\Support\Facades\View::share('app_name', 'ITAM Enterprise');
                \Illuminate\Support\Facades\View::share('company_name', 'PT CBA Chemical Industry');
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\View::share('app_name', 'ITAM Enterprise');
            \Illuminate\Support\Facades\View::share('company_name', 'PT CBA Chemical Industry');
        }
    }
}
