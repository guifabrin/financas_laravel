<?php

namespace App;

use App\Helpers\DateHelper;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{


    public $paidValues;
    public $notPaidValues;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'is_credit_card', 'automated_body', 'automated_args', 'ignore'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->paidValues = [];
        $this->notPaidValues = [];
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getOptionsInvoices($create = true)
    {
        if ($create) {
            $selectInvoices = [-1 => __('common.create')];
        } else {
            $selectInvoices = [];
        }
        foreach ($this->invoices()->get() as $invoice) {
            $selectInvoices[$invoice->id] = $invoice->id . "/" . $invoice->description;
        }
        return $selectInvoices;
    }

    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }

    public function invoicesIn($year, $month)
    {
        $period = DateHelper::getYearPeriods($year);
        return $this->invoices()->whereBetween('debit_date', [$period->init[$month], $period->end[$month]])->get();
    }

    public function transactionsIn($year, $month)
    {
        $period = DateHelper::getYearPeriods($year);
        //var_dump(explode(' ', $period->init[$month])[0], explode(' ', $period->end[$month])[0]);
        return $this->transactions()->where('date', '>=', explode(' ', $period->init[$month])[0])->where('date', '<=', explode(' ', $period->end[$month])[0])->get();
    }

    public function fillValues(int $year)
    {
        $this->paidValues[$year] = [];
        $this->notPaidValues[$year] = [];
        $period = DateHelper::getYearPeriods($year);
        if ($this->is_credit_card) {
            for ($i = 0; $i < 12; $i++) {
                $this->paidValues[$year][$i] = 0;
                $this->notPaidValues[$year][$i] = 0;
                $invoices = $this->invoices()->whereBetween('debit_date', [$period->init[$i], $period->end[$i]])->get();
                foreach ($invoices as $invoice) {
                    $this->paidValues[$year][$i] += $invoice->total();
                }
            }
        } else {
            for ($i = 0; $i < 12; $i++) {
                $this->notPaidValues[$year][$i] = $this->getTotalNotPaidFrom($period->end[$i]);
                $this->paidValues[$year][$i] = $this->getTotalPaidFrom($period->end[$i]);
            }
        }
    }

    public function getTotalNotPaidFrom($dateEnd)
    {
        return $this->notPaidTransactions()->where('date', '<=', $dateEnd)->sum('value');
    }

    public function notPaidTransactions()
    {
        return $this->transactions()->where('paid', false);
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

    public function getTotalPaidFrom($dateEnd)
    {
        return $this->paidTransactions()->where('date', '<=', $dateEnd)->sum('value');
    }

    public function paidTransactions()
    {
        return $this->transactions()->where('paid', true);
    }
}
