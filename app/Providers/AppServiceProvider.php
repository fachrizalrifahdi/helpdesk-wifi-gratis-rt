<?php

namespace App\Providers;

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
        \Illuminate\Support\Facades\View::composer('layouts.dashboard', function ($view) {
            $view->with('unassigned_tickets', \App\Models\Tiket::where('status', 'Open')->where('is_read', false)->latest()->take(5)->get());
            $view->with('unassigned_count', \App\Models\Tiket::where('status', 'Open')->where('is_read', false)->count());
        });
    }
}
