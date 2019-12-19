<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Infoexam\Password\Password;
use Orchestra\Testbench\TestCase;

class PasswordTest extends TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }

    /**
     * Get package providers.
     *
     * @param Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            Infoexam\Password\PasswordServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('auth.providers.users.driver', 'infoexam');

        $app['config']->set('auth.providers.users.model', User::class);

        $app['config']->set('database.default', 'testing');
    }

    public function test_auth()
    {
        User::query()->create([
            'username' => 'test',
            'password' => bcrypt('test'),
            'version' => 1,
        ]);

        Auth::attempt(['username' => 'test', 'password' => 'test']);

        $auth1 = Auth::user();

        $this->assertEquals(
            Password::VERSION,
            $auth1->getAttribute('version')
        );

        Auth::attempt(['username' => 'test', 'password' => 'test']);

        $this->assertSame(
            $auth1->getAttribute('password'),
            Auth::user()->getAttribute('password')
        );
    }

    public function test_bcrypt_rounds()
    {
        $this->assertStringContainsString(
            sprintf('$%d$', Password::ROUNDS),
            bcrypt('apple')
        );
    }
}
