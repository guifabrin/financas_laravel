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
        $modeView = ModeViewHelper::check($request, 'constants.user_configs.mode_account_view');
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
        $sum = (object)['paid' => [], 'notPaid' => []];
        for ($month = 0; $month < 12; $month++) {
            $sum->paid[$month] = 0;
            $sum->notPaid[$month] = 0;
            foreach ($accounts as $account) {
                $sum->paid[$month] += $account->paidValues[$year][$month];
                $sum->notPaid[$month] += $account->notPaidValues[$year][$month];
            }
        }
        return view('accounts.index', [
            'accounts' => $accounts,
            'years' => $years,
            'actual' => (object)['year' => $year, 'month' => date('n') - 1],
            'sum' => $sum,
            'modeView' => $modeView
        ]);
    }

    public function create()
    {
        return view('accounts.form');
    }

    public function store(Request $request, Account $account)
    {
        $account->description = $request->description;
        $account->is_credit_card = !!$request->is_credit_card;
        $account->user()->associate($request->user());
        $account->save();
        return view('layouts.reload');
    }

    public function edit(Account $account)
    {
        return view('accounts.form', [
            'account' => $account
        ]);
    }

    public function update(Request $request, Account $account)
    {
        $account->description = $request->description;
        $account->save();
        return view('layouts.reload');
    }

    public function destroy(Account $account)
    {
        $account->delete();
        return redirect('/accounts');
    }
}
