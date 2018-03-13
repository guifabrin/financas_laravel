<?php

namespace App\Http\Controllers;

use App\Transaction;
use Validator;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($accountId)
    {
        if (!$accountId || !($account = \Auth::user()->accounts->where('id',$accountId)->first())){
            return redirect('/accounts')->withErrors([__('accounts.not_your_account')]);
        } else {
            return view('transactions.index', ['account' => $account, 'transactions' => $account->transactions]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($accountId)
    {
        if (!$accountId || !($account = \Auth::user()->accounts->where('id',$accountId)->first())){
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
        if (!$accountId || !($account = \Auth::user()->accounts->where('id',$accountId)->first())){
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
            return redirect('/transactions/'.$account->id);
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
