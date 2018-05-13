<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Account;

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $accounts = \Auth::user()->accounts()->where('is_credit_card',false)->paginate(10);
        $actualYear = isset($request->year)?$request->year:date('Y');
        $yearDiff = (date('Y')-$actualYear);
        $j = 10-$yearDiff;
        if ($j<=0){
          $j=1;
        }
        $years = [];
        for ($i=$actualYear-$j; $i<=$actualYear; $i++){
          $years[] = $i;
        }
        if ($actualYear<date('Y')){
          for ($i=$actualYear+1; $i<=date('Y'); $i++){
            $years[] = $i;
          }
        }
        $monthValueAccount = [];
        $monthValueAccountNotPaid = [];
        $dateInit = [];
        $dateEnd = [];
        for($i=0; $i<12; $i++) {
          $dateInit[$i] = date("Y-m-d", strtotime(date($actualYear.'-'.($i+1).'-1')));
          $dateEnd[$i] = date('Y-m-t', strtotime($dateInit[$i]));
        }
        $accountsResult = [];
        foreach($accounts as $account){
          $accountResult = new \stdClass;
          $accountResult->invoice = false;
          $accountResult->id = $account->id;
          $accountResult->description = $account->description;
          $monthValueAccount[$account->id] = [];
          $monthValueAccountNotPaid[$account->id] = [];
          for($i=0; $i<12; $i++) {
            $monthValueAccountNotPaid[$account->id][$i] = $account->transactions()->where('paid', false)->where('date','<=',$dateEnd[$i])->sum('value'); 
            $monthValueAccount[$account->id][$i] = $account->transactions()->where('paid', true)->where('date','<=',$dateEnd[$i] )->sum('value');
          }
          $accountsResult[] = $accountResult;
          foreach($account->creditCards() as $creditCard){
            $accountResult = new \stdClass;
            $accountResult->invoice = true;
            $accountResult->id = $creditCard->id;
            $accountResult->description = $creditCard->description;
            $monthValueAccount[$creditCard->id] = [];
            $monthValueAccountNotPaid[$creditCard->id] = [];
            for($i=0; $i<12; $i++) {
              $invoice = $creditCard->invoices()->whereBetween('debit_date',[$dateInit[$i], $dateEnd[$i]])->first();
              if (isset($invoice)){
                $value = $invoice->transactions()->sum('value');
                $lastInvoices = $creditCard->invoices()->where('id','<',$invoice->id)->get();
                foreach ($lastInvoices as $lastInvoice){
                  $value+=$lastInvoice->transactions()->sum('value');
                }
                if ($creditCard->closed && $creditCard->debit_date>=date()){
                  $monthValueAccount[$creditCard->id][$i] = $value; 
                } else {
                  $monthValueAccountNotPaid[$creditCard->id][$i] = $value; 
                }
              } else {
                $monthValueAccount[$creditCard->id][$i] = 0;
                $monthValueAccountNotPaid[$creditCard->id][$i] = 0; 
              }
            }
            $accountsResult[] = $accountResult;
          }
        }
        $sumPaid = [];
        for($i=0; $i<12; $i++) {
          $sumPaid[$i] = 0;
          $sumNotPaid[$i] = 0;
          foreach ($accountsResult as $account) {
            $sumPaid[$i] += $monthValueAccount[$account->id][$i];
            $sumNotPaid[$i] += $monthValueAccountNotPaid[$account->id][$i];
          }
        }
        return view('accounts.index', ['accounts' => $accountsResult, 'years'=>$years, 'actualYear'=>$actualYear, 'dateInit'=>$dateInit, 'dateEnd'=>$dateEnd, 'monthValueAccount'=>$monthValueAccount, 'monthValueAccountNotPaid'=>$monthValueAccountNotPaid, 'sumPaid'=>$sumPaid, 'sumNotPaid'=>$sumNotPaid]);
    }

    private function getOptionsPreferDebitAccount(){
        $accounts = \Auth::user()->accounts;
        $selectAccounts = [null=>__('common.none')];
        foreach($accounts as $account){
            if (!$account->is_credit_card){
                $selectAccounts[$account->id] = $account->id."/".$account->description;
            }
        }
        return $selectAccounts;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $selectAccounts = $this->getOptionsPreferDebitAccount();
        return view('accounts.form', ['selectAccounts' => $selectAccounts, 'action' => __('common.add') ]);
    }

    private function valid($request){
        return Validator::make($request->all(),[
            'description' => 'required|min:5|max:50'
        ], [
            'description.required' => __('common.description_required'),
            'description.min' => __('common.description_min_5'),
            'description.max' => __('common.description_max_50')
        ])->after(function ($validator) use ($request){
            if ($request->is_credit_card) {
                if ($request->prefer_debit_account_id!=null){
                    $prefer_debit_account = \Auth::user()->accounts->where('id',$request->prefer_debit_account_id)->first();
                    if (!$prefer_debit_account){
                        $validator->errors()->add('id', __('accounts.not_your_account'));
                    }
                }
            }
        })->validate();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->valid($request);

        $account = new Account;

        $account->description = $request->description;
        $account->user()->associate(\Auth::user());
        
        $account->is_credit_card = $request->is_credit_card==null?false:$request->is_credit_card;
        if ($account->is_credit_card){
            $prefer_debit_account = Account::find($request->prefer_debit_account_id);
            if ($prefer_debit_account){
                $account->preferDebitAccount()->associate($prefer_debit_account);
            }
        }
        $account->save();
        return redirect('/accounts');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $account = \Auth::user()->accounts->where('id',$id)->first();
        $selectAccounts = $this->getOptionsPreferDebitAccount();
        return view('accounts.form', ['account'=>$account, 'selectAccounts' => $selectAccounts, 'action' => __('common.edit') ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->valid($request);

        $account = \Auth::user()->accounts->where('id',$id)->first();

        $account->description = $request->description;
        $account->user()->associate(\Auth::user());
        
        if ($account->is_credit_card){
            $prefer_debit_account = \Auth::user()->accounts->where('id',$request->prefer_debit_account_id)->first();
            if ($prefer_debit_account){
                $account->preferDebitAccount()->associate($prefer_debit_account);
            }
        } 

        $account->save();
        return redirect('/accounts');
    }

    public function confirm($id){
        $account = \Auth::user()->accounts->where('id',$id)->first();
        return view('accounts.confirm', ['account'=>$account]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        foreach (\Auth::user()->accounts as $account){
            if($account->prefer_debit_account_id == $id){
                $account->prefer_debit_account_id = null;
                $account->save();
            }
        }
        $account = \Auth::user()->accounts->where('id',$id)->first();
        $account->delete();
        return redirect('/accounts');

    }
}
