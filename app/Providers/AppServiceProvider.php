<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;

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
        Paginator::useBootstrapFive();

        // Implicitly grant "Super Admin" role all permissions
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
                \Illuminate\Support\Facades\View::share('settings', $settings);
                \Illuminate\Support\Facades\View::share('app_name', $settings['app_name'] ?? 'ITAM Enterprise');
                \Illuminate\Support\Facades\View::share('company_name', $settings['company_name'] ?? 'PT CBA Chemical Industry');
                \Illuminate\Support\Facades\View::share('company_email', $settings['company_email'] ?? 'itcbachemical23@gmail.com');

                // Dynamic Mail Configuration
                if (($settings['email_notification'] ?? '0') == '1') {
                    config([
                        'mail.mailers.smtp.host' => $settings['smtp_host'] ?? config('mail.mailers.smtp.host'),
                        'mail.mailers.smtp.port' => $settings['smtp_port'] ?? config('mail.mailers.smtp.port'),
                        'mail.mailers.smtp.username' => $settings['smtp_username'] ?? config('mail.mailers.smtp.username'),
                        'mail.mailers.smtp.password' => $settings['smtp_password'] ?? config('mail.mailers.smtp.password'),
                        'mail.from.address' => $settings['company_email'] ?? config('mail.from.address'),
                        'mail.from.name' => $settings['app_name'] ?? config('mail.from.name'),
                    ]);
                }
            } else {
                \Illuminate\Support\Facades\View::share('app_name', 'ITAM Enterprise');
                \Illuminate\Support\Facades\View::share('company_name', 'PT CBA Chemical Industry');
                \Illuminate\Support\Facades\View::share('company_email', 'itcbachemical23@gmail.com');
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\View::share('app_name', 'ITAM Enterprise');
            \Illuminate\Support\Facades\View::share('company_name', 'PT CBA Chemical Industry');
            \Illuminate\Support\Facades\View::share('company_email', 'itcbachemical23@gmail.com');
        }
    }
}
