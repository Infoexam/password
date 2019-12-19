<?php

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements Authenticatable
{
    use Illuminate\Auth\Authenticatable;

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'testing';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
