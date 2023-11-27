<?php

namespace App\Providers;

use App\Models\PermissionsView;
use App\Models\Parameter;
use App\Models\PositionsView;
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

        $permissions = PermissionsView::select('id','name','description1','description2','description3','pid','level','sort','icon','role','order')->get();
        view()->share('permissions', $permissions);
        $menu = \Request::segment(1);
        view()->share('menu', $menu);
    }
}
