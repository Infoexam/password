<?php

namespace Infoexam\Password;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class InfoexamUserProvider extends EloquentUserProvider
{
    /**
     * Validate a user against the given credentials.
     *
     * @param UserContract $user
     * @param array $credentials
     *
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        $password = new Password($user, $credentials);

        return $this->hasher->check($password->userPassword(), $user->getAuthPassword());
    }
}
