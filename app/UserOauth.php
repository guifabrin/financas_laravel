<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserOauth extends Model
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'avatar', 'uuid'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
