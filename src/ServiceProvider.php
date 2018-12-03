<?php

namespace Wdy\District;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(District::class, function () {
            return new District(config('services.district.key'));
        });

        $this->app->alias(District::class, 'district');
    }

    public function provides()
    {
        return [District::class, 'district'];
    }
}
