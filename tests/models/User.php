<?php

class User extends \Illuminate\Database\Eloquent\Model implements Illuminate\Contracts\Auth\Authenticatable
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
