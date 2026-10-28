<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\WorkoutSession;
use App\Observers\WorkoutSessionObserver;
use App\Observers\RoutineObserver;
use App\Models\Routine;
use App\Observers\ExerciseObserver;
use App\Models\Exercise;

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
        WorkoutSession::observe(WorkoutSessionObserver::class);
        Routine::observe(RoutineObserver::class);
        Exercise::observe(ExerciseObserver::class);
    }
}
