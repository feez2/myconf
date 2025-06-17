<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
        Blade::directive('sessionTypeBadge', function ($type) {
            return "<?php
                switch($type) {
                    case 'keynote': echo 'badge-keynote'; break;
                    case 'workshop': echo 'badge-workshop'; break;
                    case 'panel': echo 'badge-panel'; break;
                    default: echo 'badge-primary';
                }
            ?>";
        });
    }
}
