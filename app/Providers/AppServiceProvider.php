<?php

namespace App\Providers;

use App\Contracts\ArticleServiceInterface;
use App\Contracts\UserFavoriteInterface;
use App\Services\ArticleService;
use App\Services\UserFavoriteService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ArticleServiceInterface::class, ArticleService::class);
        $this->app->bind(UserFavoriteInterface::class, UserFavoriteService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
