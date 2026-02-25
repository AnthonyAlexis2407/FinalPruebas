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
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            if (\Illuminate\Support\Facades\Auth::check()) {
                $activeStoreId = session('active_store_id');
                $view->with('activeStore', \App\Models\Store::find($activeStoreId));
                $view->with('availableStores', \App\Models\Store::all());
            }
        });
    }
}
