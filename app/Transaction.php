<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
  
  protected $fillable = [
      'description', 'value', 'date', 'paid'
  ];
  public function account()
  {
      return $this->belongsTo('App\Account', 'account_id');
  }
}
