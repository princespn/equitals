<?php

namespace App\Providers;

use App\Policies\BinaryIncomeService;
use App\Policies\BinaryIncomeInterface;
use Illuminate\Support\ServiceProvider;

class BinaryIncomeProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
         $this->app->bind('App\Policies\Contracts\BinaryIncomeInterface', function ($app) {
          return new BinaryIncomeService();
        });
    }
}
