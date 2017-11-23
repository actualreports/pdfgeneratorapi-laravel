<?php

namespace ActualReports\PDFGeneratorAPILaravel;

/**
 * Class ServiceProvider
 *
 * @package ActualReports\PDFGeneratorAPILaravel
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/pdfgeneratorapi.php' => config_path('pdfgeneratorapi.php')
        ], 'config');

        $this->publishes([
            __DIR__.'/../assets/js' => resource_path('assets/vendor/pdfgeneratorapi'),
        ], 'public');

        $this->loadRoutesFrom(__DIR__.'/../routes/templates.php');
    }

    /**
     * @return void
     */
    public function register()
    {
        $this->app->singleton('pdfgeneratorapi', function ($app) {
            $client = new \ActualReports\PDFGeneratorAPI\Client(
                config('pdfgeneratorapi.token'),
                config('pdfgeneratorapi.secret'),
                config('pdfgeneratorapi.default_workspace')
            );

            $client->setBaseUrl(config('pdfgeneratorapi.base_url'));

            return $client;
        });
    }
}