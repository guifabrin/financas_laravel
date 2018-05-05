<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'is_credit_card', 'prefer_debit_account_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function preferDebitAccount()
    {
        return $this->belongsTo('App\Account', 'prefer_debit_account_id');
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

    public function creditCards()
    {
        return Account::where('prefer_debit_account_id',$this->id)->get();
    }

    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }

    public function getOptionsInvoices($create = true){
        if ($create){
            $selectInvoices = [-1 =>__('common.create')];
        } else {
            $selectInvoices = [];
        }
        foreach($this->invoices()->get() as $invoice){
            $selectInvoices[$invoice->id] = $invoice->id."/".$invoice->description;
        }
        return $selectInvoices;
    }
}