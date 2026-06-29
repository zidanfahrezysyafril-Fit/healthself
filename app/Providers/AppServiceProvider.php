<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\MoodRepositoryInterface;
use App\Repositories\Eloquent\MoodRepository;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use App\Repositories\Eloquent\ArticleRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MoodRepositoryInterface::class, MoodRepository::class);
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
