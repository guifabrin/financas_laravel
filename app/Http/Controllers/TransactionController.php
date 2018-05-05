<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\Invoice;
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
            if (isset($request->invoice_id)){
                $transactions =  $account->invoices()->where('id',$request->invoice_id)->first()->transactions()->orderBy('date')->orderBy('description');
            } else {
                $transactions =  $account->transactions()->orderBy('date')->orderBy('description');
                if ($request->input('date_init')!==null && $request->input('date_end')!==null){
                    $transactions->whereBetween('date',[$request->input('date_init'), $request->input('date_end')]);
                } else {
                    $transactions->whereBetween('date',[date('Y-m-01'), date('Y-m-t')]);
                }
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


    private function valid($request, $account){
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
        ])->after(function ($validator) use ($request, $account){
            if ($account->is_credit_card) {
                if ($request->invoice_id==null){
                    $validator->errors()->add('invoice_id', __('transactions.need_set_invoice'));
                }
                if ($request->invoice_id==-1){
                    if ($request->invoice_description==null || strlen($request->invoice_description)<5){
                        $validator->errors()->add('invoice_id', __('transactions.invoice_description_min_5'));    
                    }
                    if ($request->invoice_date_init==null){
                        $validator->errors()->add('invoice_id', __('transactions.invoice_date_init_required'));    
                    }
                    if ($request->invoice_date_end==null){
                        $validator->errors()->add('invoice_id', __('transactions.invoice_date_end_required'));    
                    }
                    if ($request->invoice_debit_date==null){
                        $validator->errors()->add('invoice_id', __('transactions.invoice_debit_date_required'));    
                    }
                }
            }
        })->validate();
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
            $this->valid($request, $account);
            $invoice_id = null;
            if ($request->invoice_id==-1){
                $invoice = new Invoice;
                $invoice->account()->associate($account);
                $invoice->description = $request->invoice_description;
                $invoice->date_init = $request->invoice_date_init;
                $invoice->date_end = $request->invoice_date_end;
                $invoice->debit_date = $request->invoice_debit_date;
                $invoice->save();
                $invoice_id = $invoice->id;
            } else if ($request->invoice_id!=null){
                $invoice_id = $request->invoice_id;    
            }
            $transaction = new Transaction;
            $transaction->account()->associate($account);
            $transaction->date = $request->date;
            $transaction->description =$request->description;
            $transaction->value = $request->value;
            $transaction->paid = isset($request->paid)?$request->paid:false;
            $transaction->invoice_id = $invoice_id;
            $transaction->save();
            return redirect('/account/'.$account->id.'/transactions/'.
                (
                    (isset($_GET) && isset($_GET['date_init']) && isset($_GET['date_end']))
                    ? '?date_init='.$_GET['date_init'].'&date_end='.$_GET['date_end'] : ''));    
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
    {   $date_query = (isset($_GET['date_init']) && isset($_GET['date_end'])) ? '?date_init='.$_GET['date_init'].'&date_end='.$_GET['date_end'] : '';
        $account = $this->verifyAccount($accountId);
        if (!$account){
            return redirect('/accounts')->withErrors([__('accounts.not_your_account')]);
        } else {
            $this->valid($request, $account);
            $transaction = $this->verifyTransaction($account, $transactionId);
            if (!$transaction){
                return redirect('/account/'.$account->id.'/transactions'.$date_query)->withErrors([__('transactions.not_your_transaction')]);
            } else {
                $paid = isset($request->paid)?$request->paid:false;
               if ($request->invoice_id==-1){
                    $invoice = new Invoice;
                    $invoice->account()->associate($account);
                    $invoice->description = $request->invoice_description;
                    $invoice->date_init = $request->invoice_date_init;
                    $invoice->date_end = $request->invoice_date_end;
                    $invoice->debit_date = $request->invoice_debit_date;
                    $invoice->save();
                    $invoice_id = $invoice->id;
                } else if ($request->invoice_id!=null){
                    $invoice_id = $request->invoice_id;    
                }
                $transaction->date = $request->date;
                $transaction->description =$request->description;
                $transaction->value = $request->value;
                $transaction->paid = $paid;
                $transaction->invoice_id = $invoice_id;
                $transaction->save();
                $account->save();
                return redirect('/account/'.$account->id.'/transactions'.$date_query);
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
                $transaction->delete();
                $account->save();
                return redirect('/account/'.$account->id.'/transactions');
            }
        }
    }

    public function invoices($accountId){

        $account = $this->verifyAccount($accountId);
        if (!$account){
            return redirect('/accounts')->withErrors([__('accounts.not_your_account')]);
        } else {
            return view('transactions.invoices', ['account' => $account]);
        }
    }
}
