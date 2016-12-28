<?php

namespace Infoexam\Password;

use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\Login;

class AuthEventSubscriber
{
    /**
     * @var array
     */
    protected static $credentials = [];

    /**
     * Handle user attempting events.
     *
     * @param Attempting $attempting
     */
    public function onUserAttempting(Attempting $attempting)
    {
        self::$credentials = $attempting->credentials;
    }

    /**
     * Handle user login events.
     *
     * @param Login $login
     */
    public function onUserLogin(Login $login)
    {
        $password = new Password($login->user, self::$credentials);

        if ($password->needUpgrade()) {
            $login->user->update([
                'password' => bcrypt($password->passwordLatestVersion()),
                'version' => $password->currentVersion(),
            ]);
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            Attempting::class,
            'Infoexam\Password\AuthEventSubscriber@onUserAttempting'
        );

        $events->listen(
            Login::class,
            'Infoexam\Password\AuthEventSubscriber@onUserLogin'
        );
    }
}
