<?php

namespace App\Http\Controllers;

use App\Account;
use App\Helpers\DateHelper;
use App\Helpers\ModeViewHelper;
use Illuminate\Http\Request;


class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $modeView = ModeViewHelper::mode($request, 'constants.user_configs.mode_account_view');
        $year = date('Y');
        if ($modeView == 'table') {
            $year = $request->year ?? $year;
            $years = DateHelper::getListYear($year);
        } else {
            $years = [$year];
        }
        $accounts = $request->user()->accounts()->get();
        foreach ($accounts as $account) {
            $account->fillValues($year);
        }
        $accounts = $accounts->sortBy(function ($a, $key) {
            $s = $a->is_credit_card ? 0.2 : 0.1;
            $i = $a->is_credit_card ? $a->prefer_debit_account_id : $a->id;
            $b = $a->ignore ? 1000 : 0;
            return ($i + $s + $b);
        });
        $sum = (object)['paid' => [], 'notPaid' => []];
        for ($month = 0; $month < 12; $month++) {
            $sum->paid[$month] = 0;
            $sum->notPaid[$month] = 0;
            foreach ($accounts as $account) {
                if ($account->ignore) {
                    continue;
                }
                $sum->paid[$month] += $account->paidValues[$year][$month];
                $sum->notPaid[$month] += $account->notPaidValues[$year][$month];
            }
        }
        return view_theme($request, 'accounts.index', [
            'accounts' => $accounts,
            'years' => $years,
            'actual' => (object)['year' => $year, 'month' => date('n') - 1],
            'sum' => $sum,
            'modeView' => $modeView
        ]);
    }

    public function create(Request $request)
    {
        return view_theme($request, 'accounts.form');
    }

    public function store(Request $request, Account $account)
    {
        $account->description = $request->description;
        $account->is_credit_card = !!$request->is_credit_card;
        $account->ignore = !!$request->ignore;
        $account->user()->associate($request->user());
        $account->save();
        return view_theme($request, 'layouts.reload');
    }

    public function edit(Request $request, Account $account)
    {
        return view_theme($request, 'accounts.form', [
            'account' => $account
        ]);
    }

    public function update(Request $request, Account $account)
    {
        $account->description = $request->description;
        $account->ignore = !!$request->ignore;
        $account->save();
        return view_theme($request, 'layouts.reload');
    }

    public function destroy(Account $account)
    {
        $account->delete();
        return redirect('/accounts');
    }
}
