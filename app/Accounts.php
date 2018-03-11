<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accounts extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'is_credit_card', 'prefer_debit_account_id', 'credit_close_day', 'debit_day'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function preferDebitAccount()
    {
        return $this->belongsTo('App\Accounts', 'prefer_debit_account_id');
    }
}
