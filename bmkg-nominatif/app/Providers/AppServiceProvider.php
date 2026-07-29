<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Use Tailwind pagination views
        Paginator::useTailwind();

        // Set locale for Carbon date translation (ID)
        Date::setLocale('id');

        // Strict model to prevent lazy loading (optional for dev, tighten for prod)
        // Model::shouldBeStrict(! app()->isProduction());

        // Prevent mass assignment protection issues
        Model::unguard(false);
    }
}
