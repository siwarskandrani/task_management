<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

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
    public function boot()
    {
        // Attacher les notifications à la vue 'layouts.navigation'
        View::composer('layouts.navigation', function ($view) {
            $view->with('notifications', Auth::user()->unreadNotifications);
        });
    }
}
