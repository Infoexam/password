<?php

use Infoexam\Password\Password;

class PasswordTest extends Orchestra\Testbench\TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->loadMigrationsFrom([
            '--realpath' => realpath(__DIR__.'/migrations'),
        ]);
    }

    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            Infoexam\Password\PasswordServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('auth.providers.users.driver', 'infoexam');
        $app['config']->set('auth.providers.users.model', User::class);

        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    public function test_auth()
    {
        User::create(['username' => 'test', 'password' => bcrypt('test'), 'version' => 1])->fresh();

        Auth::attempt(['username' => 'test', 'password' => 'test']);

        $auth1 = Auth::user();

        $this->assertEquals(Password::VERSION, $auth1->getAttribute('version'));

        Auth::attempt(['username' => 'test', 'password' => 'test']);

        $auth2 = Auth::user();

        $this->assertSame($auth1->getAttribute('password'), $auth2->getAttribute('password'));
    }

    public function test_bcrypt_rounds()
    {
        $rounds = Password::ROUNDS;

        $this->assertContains("\${$rounds}\$", bcrypt('apple'));
    }
}
