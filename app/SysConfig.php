<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SysConfig extends Model
{
    protected $table = 'configs';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description'
    ];
}