<?php

namespace Gldrenthe89\NovaStringGeneratorField;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class NovaStringGeneratorFieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('generate-string', __DIR__.'/../dist/js/field.js');
            Nova::style('generate-string', __DIR__.'/../dist/css/field.css');
        });
    }
}
