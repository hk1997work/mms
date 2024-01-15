<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $permissions = Permission::orderBy('sort')->get();
        view()->share('permissions', $permissions);
        $menu = \Request::segment(1);
        view()->share('menu', $menu);
    }
}
