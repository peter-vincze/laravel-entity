<?php

namespace App\Providers;

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


        /*class MyCustomRulesProvider implements ProvidesValidationRules {
            public function validationRules() {

            }
        }

        $this->app->bind(ProvidesValidationRules::class, MyCustomRulesProvider::class);        */
    }
}
