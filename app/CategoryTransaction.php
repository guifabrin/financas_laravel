<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryTransaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id', 'transaction_id'
    ];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function transaction()
    {
        return $this->belongsTo('App\Transaction');
    }
}