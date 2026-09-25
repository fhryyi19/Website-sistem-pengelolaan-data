<?php

namespace App\Providers;

use App\Models\Education;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Rank;
use App\Models\SalaryHistory;
use App\Models\User;
use App\Models\WorkUnit;
use App\Observers\MobileSyncObserver;
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

        foreach ([Employee::class, WorkUnit::class, Position::class, Rank::class, Education::class, User::class, SalaryHistory::class] as $model) {
            $model::observe(MobileSyncObserver::class);
        }
    }
}
