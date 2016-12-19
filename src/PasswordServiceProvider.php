<?php

namespace Infoexam\Password;

use Illuminate\Support\ServiceProvider;

class PasswordServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['hash']->setRounds(Password::ROUNDS);

        $this->app['events']->subscribe(AuthEventSubscriber::class);

        $this->app['auth']->provider('infoexam', function ($app, array $config) {
            return new InfoexamUserProvider($app['hash'], $config['model']);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
