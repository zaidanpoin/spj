<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Helpers\BreadcrumbHelper;

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
        // Share breadcrumbs dengan semua views
        View::composer('*', function ($view) {
            if (request()->route()) {
                $breadcrumbs = BreadcrumbHelper::generate();
                $view->with('breadcrumbs', $breadcrumbs);
            }
        });
    }
}
