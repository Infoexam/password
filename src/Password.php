<?php

namespace Infoexam\Password;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class Password
{
    /**
     * Current password version.
     *
     * @var int
     */
    const VERSION = 2;

    /**
     * The algorithmic cost that should be used.
     *
     * @var int
     */
    const ROUNDS = 13;

    /**
     * @var UserContract
     */
    protected $user;

    /**
     * @var array
     */
    protected $credentials;

    /**
     * Constructor.
     *
     * @param UserContract $user
     * @param array $credentials
     */
    public function __construct(UserContract $user, array $credentials)
    {
        $this->user = $user;

        $this->credentials = $credentials;
    }

    /**
     * Get password latest version.
     *
     * @return string
     */
    public function passwordLatestVersion()
    {
        $method = 'passwordVersion'.$this->currentVersion();

        return $this->{$method}();
    }

    /**
     * Get password version 1.
     *
     * @return string
     */
    public function passwordVersion1()
    {
        return $this->credentials['password'];
    }

    /**
     * Get password version 2.
     *
     * @return string
     */
    public function passwordVersion2()
    {
        $mixed = implode('|', [
            $this->credentials['password'],
            $this->user->getAttribute('username'),
        ]);

        return base64_encode(hash('sha512', $mixed, true));
    }

    /**
     * Determinate user password need upgrade.
     *
     * @return bool
     */
    public function needUpgrade()
    {
        return $this->currentVersion() !== $this->userVersion();
    }

    /**
     * Get user password.
     *
     * @return string
     */
    public function userPassword()
    {
        $method = 'passwordVersion'.$this->userVersion();

        return $this->{$method}();
    }

    /**
     * Get user password version.
     *
     * @return int
     */
    protected function userVersion()
    {
        return intval($this->user->getAttribute('version'));
    }

    /**
     * Get current password version.
     *
     * @return int
     */
    public function currentVersion()
    {
        return self::VERSION;
    }
}
