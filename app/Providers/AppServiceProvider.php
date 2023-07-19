<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\Thread;
use App\Observers\PostObserver;
use App\Observers\ThreadObserver;
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
        Post::observe(PostObserver::class);
        Thread::observe(ThreadObserver::class);
    }
}
