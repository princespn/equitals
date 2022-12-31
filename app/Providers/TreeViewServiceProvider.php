<?php

namespace App\Providers;

use App\Policies\TreeViewStructure;
use Illuminate\Support\ServiceProvider;

class TreeViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
       //return $TreeViewStructureObj = new TreeViewStructure;
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Policies\Contracts\TreeViewInterface', function ($app) {
          return new TreeViewStructure();
        });
        //$TreeViewStructureObj->testFunction();
    }
}
