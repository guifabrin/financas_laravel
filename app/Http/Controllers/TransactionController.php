<?php

namespace App\Http\Controllers;

use App\Transaction;
use Validator;
use Illuminate\Http\Request;

class TransactionController extends Controller
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

    private function verifyTransaction($account, $transactionId){
        if (!$transactionId || !($transaction = $account->transactions->where('id', $transactionId)->first())){
            return false;
        } else {
            return $transaction;
        }
    }

    private function verifyAccount($accountId){
        if (!$accountId || !($account = \Auth::user()->accounts->where('id',$accountId)->first())){
            return false;
        } else {
            return $account;
        }

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $accountId)
    {
        $account = $this->verifyAccount($accountId);
        if (!$account){
            return redirect('/accounts')->withErrors([__('accounts.not_your_account')]);
        } else {
            $transactions =  $account->transactions()->orderBy('date')->orderBy('description');
            if ($request->input('date_init')!==null && $request->input('date_end')!==null){
                $transactions->whereBetween('date',[$request->input('date_init'), $request->input('date_end')]);
            } else {
                $transactions->whereBetween('date',[date('Y-m-01'), date('Y-m-t')]);
            }
            $transactions = $transactions->get();
            return view('transactions.index', ['account' => $account, 'transactions' => $transactions]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($accountId)
    {
        $account = $this->verifyAccount($accountId);
        if (!$account){
            return redirect('/accounts')->withErrors([__('accounts.not_your_account')]);
        } else {
            return view('transactions.form', ['action'=>__('common.add'),'account' => $account]);
        }
    }


    private function valid($request){
        return Validator::make($request->all(),[
            'description' => 'required|min:5|max:100',
            'date' => 'required',
            'value' => 'required'
        ], [
            'description.required' => __('common.description_required'),
            'description.min' => __('common.description_min_5'),
            'description.max' => __('common.description_max_100'),
            'date.required' => __('common.date_required'),
            'value.required' => __('common.date_required')
        ])->validate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $accountId)
    {
        $account = $this->verifyAccount($accountId);
        if (!$account){
            return redirect('/accounts')->withErrors([__('accounts.not_your_account')]);
        } else {
            $this->valid($request);
            $transaction = new Transaction;
            $transaction->account()->associate($account);
            $transaction->date = $request->date;
            $transaction->description =$request->description;
            $transaction->value = $request->value;
            $transaction->paid = isset($request->paid)?$request->paid:false;
            $transaction->save();
            if($transaction->paid){
                $account->amount += $transaction->value;
                $account->save();
            }
            return redirect('/account/'.$account->id.'/transactions/');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function edit($accountId, $transactionId)
    {
        $account = $this->verifyAccount($accountId);
        if (!$account){
            return redirect('/accounts')->withErrors([__('accounts.not_your_account')]);
        } else {
            $transaction = $this->verifyTransaction($account, $transactionId);
            if (!$transaction){
                return redirect('/account/'.$account->id.'/transactions')->withErrors([__('transactions.not_your_transaction')]);
            } else {
                return view('transactions.form', ['action'=>__('common.edit'),'account' => $account, 'transaction' => $transaction]);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $accountId, $transactionId)
    {   
        $this->valid($request);
        $account = $this->verifyAccount($accountId);
        if (!$account){
            return redirect('/accounts')->withErrors([__('accounts.not_your_account')]);
        } else {
            $transaction = $this->verifyTransaction($account, $transactionId);
            if (!$transaction){
                return redirect('/account/'.$account->id.'/transactions')->withErrors([__('transactions.not_your_transaction')]);
            } else {
                if ($transaction->paid){
                    $account->amount -= $transaction->value;
                }
                $paid = isset($request->paid)?$request->paid:false;
                if ($paid){
                    $account->amount += $request->value;
                }
                $transaction->date = $request->date;
                $transaction->description =$request->description;
                $transaction->value = $request->value;
                $transaction->paid = $paid;
                $transaction->save();
                $account->save();
                return redirect('/account/'.$account->id.'/transactions');
            }
        }
    }

    public function confirm($accountId, $transactionId){
        $account = $this->verifyAccount($accountId);
        if (!$account){
            return redirect('/accounts')->withErrors([__('accounts.not_your_account')]);
        } else {
            $transaction = $this->verifyTransaction($account, $transactionId);
            if (!$transaction){
                return redirect('/account/'.$account->id.'/transactions')->withErrors([__('transactions.not_your_transaction')]);
            } else {
                return view('transactions.confirm', ['account' => $account, 'transaction' => $transaction]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($accountId,$transactionId)
    {
        $account = $this->verifyAccount($accountId);
        if (!$account){
            return redirect('/accounts')->withErrors([__('accounts.not_your_account')]);
        } else {
            $transaction = $this->verifyTransaction($account, $transactionId);
            if (!$transaction){
                return redirect('/account/'.$account->id.'/transactions')->withErrors([__('transactions.not_your_transaction')]);
            } else {
                if ($transaction->paid){
                    $account->amount -= $transaction->value;
                }
                $transaction->delete();
                $account->save();
                return redirect('/account/'.$account->id.'/transactions');
            }
        }
    }
}
