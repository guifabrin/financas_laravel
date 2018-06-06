<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserConfig extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'config_id', 'value'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function config()
    {
        return $this->belongsTo('App\SysConfig');
    }
}