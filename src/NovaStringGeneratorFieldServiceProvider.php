<?php

namespace Gldrenthe89\NovaStringGeneratorField;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use OptimistDigital\NovaTranslationsLoader\LoadsNovaTranslations;

class NovaStringGeneratorFieldServiceProvider extends ServiceProvider
{
    use LoadsNovaTranslations;
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

        $this->loadTranslations(__DIR__ . '/../resources/lang', 'nova-string-generator-field', true);
    }
}
