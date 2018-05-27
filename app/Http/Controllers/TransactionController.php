<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\Invoice;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests\UploadOfxRequest;

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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $date_init = $request->input('date_init');
      $date_end = $request->input('date_end');
      if (isset($request->invoice)){
        $invoice = $request->account->invoices()->whereBetween('debit_date',[$date_init, $date_end])->first();
        if (isset($invoice)){
          $transactions = $invoice->transactions()->orderBy('date')->orderBy('description');
        } else {
          $transactions =  $request->account->transactions()->orderBy('date')->orderBy('description');
          if ($date_init!==null && $date_end!==null){
            $transactions->whereBetween('date',[$date_init, $date_end]);
          } else {
            $transactions->whereBetween('date',[date('Y-m-01'), date('Y-m-t')]);
          }
        }
      } else {
        $transactions =  $request->account->transactions()->orderBy('date')->orderBy('description');
        if ($date_init!==null && $date_end!==null){
          $transactions->whereBetween('date',[$date_init, $date_end]);
        } else {
          $transactions->whereBetween('date',[date('Y-m-01'), date('Y-m-t')]);
        }
      }
      $transactions = $transactions->get();
      return view('transactions.index', ['account' => $request->account, 'transactions' => $transactions]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
      return view('transactions.form', ['action'=>__('common.add'),'account' => $request->account]);
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
        ])->after(function ($validator) use ($request){
            if ($request->account->is_credit_card) {
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
    public function store(Request $request)
    {
      $this->valid($request);
      $invoice_id = null;
      if ($request->invoice_id==-1){
          $invoice = new Invoice;
          $invoice->account()->associate($request->account);
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
      $transaction->account()->associate($request->account);
      $transaction->date = $request->date;
      $transaction->description =$request->description;
      $transaction->value = $request->value;
      $transaction->paid = isset($request->paid)?$request->paid:false;
      $transaction->invoice_id = $invoice_id;
      $transaction->save();
      return redirect('/account/'.$request->account->id.'/transactions/'. ((isset($_GET) && isset($_GET['date_init']) && isset($_GET['date_end'])) ? '?date_init='.$_GET['date_init'].'&date_end='.$_GET['date_end'] : ''));    
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
      return view('transactions.form', ['action'=>__('common.edit'),'account' => $request->account, 'transaction' => $request->transaction]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {   
      $this->valid($request);
      $date_query = (isset($_GET['date_init']) && isset($_GET['date_end'])) ? '?date_init='.$_GET['date_init'].'&date_end='.$_GET['date_end'] : '';
      $paid = isset($request->paid)?$request->paid:false;
      $invoice_id = null;
      if ($request->invoice_id==-1){
          $invoice = new Invoice;
          $invoice->account()->associate($request->account);
          $invoice->description = $request->invoice_description;
          $invoice->date_init = $request->invoice_date_init;
          $invoice->date_end = $request->invoice_date_end;
          $invoice->debit_date = $request->invoice_debit_date;
          $invoice->save();
          $invoice_id = $invoice->id;
      } else if ($request->invoice_id!=null){
          $invoice_id = $request->invoice_id;    
      }
      $request->transaction->date = $request->date;
      $request->transaction->description =$request->description;
      $request->transaction->value = $request->value;
      $request->transaction->paid = $paid;
      $request->transaction->invoice_id = $invoice_id;
      $request->transaction->save();
      $request->account->save();
      return redirect('/account/'.$request->account->id.'/transactions'.$date_query);
    }

    public function confirm(Request $request){
      return view('transactions.confirm', ['account' => $request->account, 'transaction' => $request->transaction]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
      $request->transaction->delete();
      return redirect('/account/'.$request->account->id.'/transactions');
    }

    public function getOfxAsXML($file) {
        $content = file_get_contents($file);
        $line = strpos($content, "<OFX>");
        $ofx = substr($content, $line - 1);
        $buffer = $ofx;
        $count = 0;
        while ($pos = strpos($buffer, '<')) {
            $count++;
            $pos2 = strpos($buffer, '>');
            $element = substr($buffer, $pos + 1, $pos2 - $pos - 1);
            if (substr($element, 0, 1) == '/')
                $sla[] = substr($element, 1);
            else
                $als[] = $element;
            $buffer = substr($buffer, $pos2 + 1);
        }
        $adif = array_diff($als, $sla);
        $adif = array_unique($adif);
        $ofxy = $ofx;
        foreach ($adif as $dif) {
            $dpos = 0;
            while ($dpos = strpos($ofxy, $dif, $dpos + 1)) {
                $npos = strpos($ofxy, '<', $dpos + 1);
                $ofxy = substr_replace($ofxy, "</$dif>\n<", $npos, 1);
                $dpos = $npos + strlen($element) + 3;
            }
        }
        $ofxy = str_replace('&', '&amp;', $ofxy);
        return $ofxy;
    }

    public function uploadOfx(UploadOfxRequest $request)
    {
      foreach ($request->file('ofx-file') as $file) {
        $xmlstr = $this->getOfxAsXML($file);
        $ofxParser = new \OfxParser\Parser();
        $xml = simplexml_load_string($xmlstr);
        $ofx = new \OfxParser\Ofx($xml);
        $bankAccount = reset($ofx->bankAccounts);
        $startDate = $bankAccount->statement->startDate;
        $endDate = $bankAccount->statement->endDate;
        $invoice_id = null;
        if ($request->account->is_credit_card){
            $invoice = new Invoice;
            $invoice->account()->associate($request->account);
            $invoice->description = "Invoice ".$file->getClientOriginalName();
            $invoice->date_init = $startDate;
            $invoice->date_end = $endDate;
            $invoice->debit_date = new \DateTime();
            $invoice->save();
            $invoice_id = $invoice->id; 
        }
        $transactions = $bankAccount->statement->transactions;
        foreach($transactions as $ofxTransaction){
          $transaction = new Transaction;
          $transaction->date = $ofxTransaction->date;
          $transaction->description = $ofxTransaction->memo;
          $transaction->value = $ofxTransaction->amount;
          $transaction->paid = true;
          $transaction->account_id = $request->account->id;
          if ($request->account->is_credit_card){
            $transaction->invoice_id = $invoice_id;
          }
          $transaction->save();
        }
      }
      return redirect('/accounts/');
    }
}
