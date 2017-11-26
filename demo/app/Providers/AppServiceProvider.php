<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * Override the binding for data repository contract and implementation
         */
        $this->app->bind(
            \ActualReports\PDFGeneratorAPILaravel\Contracts\DataRepository::class,
            \App\Repositories\DataRepository::class
        );
    }
}
