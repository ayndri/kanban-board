<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $unreadNotifications = $user->unreadNotifications()->limit(5)->get();
                $unreadNotificationsCount = $user->unreadNotifications()->count();

                $view->with('unreadNotifications', $unreadNotifications)
                    ->with('unreadNotificationsCount', $unreadNotificationsCount);
            } else {
                // Beri nilai default jika user belum login
                $view->with('unreadNotifications', collect())
                    ->with('unreadNotificationsCount', 0);
            }
        });
    }
}
