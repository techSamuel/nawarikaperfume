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
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            try {
                $settings = \Illuminate\Support\Facades\Cache::remember('site_settings', 60*24, function () {
                    return \App\Models\Setting::pluck('value', 'key')->toArray();
                });
            } catch (\Exception $e) {
                $settings = [];
            }
            
            $view->with('settings', $settings);
        });
    }
}
