<?php
namespace Fastbill\Laravel\Five;

use Illuminate\Support\ServiceProvider;
use Fastbill\Fasbill;

/**
 * Class FastbillServiceProvider
 *
 * @namespace    Fastbill\Laravel\Five
 * @author     Mauthi <mauthi@gmail.com>
 */
class FastbillServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $options = $this->app['config']['services.fastbill'];

        $this->app->singleton(Fastbill::class, function($app) use ($options)
        {
            $fastbill = $app['config']['services.fastbill'];
            return new Fastbill($fastbill['email'], $fastbill['api_key'], $fastbill['api_url']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Fastbill::class];
    }

}
