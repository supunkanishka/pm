<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Parameter;

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
        Schema::defaultstringLength(191);

        view()->composer('layouts/app', function ($view) {
            $parameter = Parameter::first();
            $note = "";
            if($parameter != null){
                $note = $parameter->note;
            }
            $view->with('note',$note);
        });
    }
}
