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

        $permissions = PermissionsView::get();
        view()->share('permissions', $permissions);
        /**
        $types = PositionsView::orderBy('sort')->get();
        view()->share('types', $types);
        $abcs = Parameter::where('pid', Parameter::where('name', 'ABC类')->first()->id)->orderBy('sort')->get();
        view()->share('abcs', $abcs);
        $cycles = Parameter::where('pid', Parameter::where('name', '检定周期')->first()->id)->orderBy('sort')->get();
        view()->share('cycles', $cycles);
        $states = Parameter::where('pid', Parameter::where('name', '管理状态')->first()->id)->orderBy('sort')->get();
        view()->share('states', $states);
        $departments = Parameter::where('pid', Parameter::where('name', '检定部门')->first()->id)->orderBy('sort')->get();
        view()->share('departments', $departments);
        $categories = Parameter::where('pid', Parameter::where('name', '证书类型')->first()->id)->orderBy('sort')->get();
        view()->share('categories', $categories);
        $plans = Parameter::where('pid', Parameter::where('name', '检定计划')->first()->id)->orderBy('sort')->get();
        view()->share('plans', $plans);
        **/
        $menu = \Request::segment(1);
        view()->share('menu', $menu);
    }
}
