<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoleAndPermission;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'picture', 'is_root'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function userOauths()
    {
        return $this->hasMany('App\UserOauth');
    }

    public function accounts()
    {
        return $this->hasMany('App\Account');
    }

    public function creditCards()
    {
        return $this->hasMany('App\Account')->where('is_credit_card', true);
    }

    public function configs()
    {
        return $this->hasMany('App\UserConfig');
    }

    public function categories()
    {
        return $this->hasMany('App\Category');
    }
}
