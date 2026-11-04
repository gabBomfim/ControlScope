<?php

namespace App\Providers;

use App\Domain\Posture\Contracts\ScoringStrategy;
use App\Domain\Posture\Scoring\IllustrativeScoringStrategy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ScoringStrategy::class, IllustrativeScoringStrategy::class);
    }

    public function boot(): void {}
}
