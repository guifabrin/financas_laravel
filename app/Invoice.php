<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'description', 'date_init', 'date_end', 'debit_date', 'closed'
    ];

    public function account()
    {
        return $this->belongsTo('App\Account','account_id');
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction','invoice_id');
    }
}