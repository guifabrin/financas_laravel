<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Account;
use App\Invoice;

class InvoiceController extends Controller
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

	public function index(Request $request)
  {
    $invoices = $request->account->invoices()->orderBy('debit_date')->get();
    return view('invoices.index', ['account' => $request->account, 'invoices' => $invoices]);
  }   

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create(Request $request)
  {
    return view('invoices.form', ['action'=>__('common.add'), 'account' => $request->account]);
  }

  private function valid($request){
    return Validator::make($request->all(),[
        'description' => 'required|min:5|max:100',
        'date_init' => 'required',
        'date_end' => 'required',
        'debit_date' => 'required'
    ], [
        'description.required' => __('common.description_required'),
        'description.min' => __('common.description_min_5'),
        'description.max' => __('common.description_max_100'),
        'date_init.required' => __('common.date_required'),
        'date_end.required' => __('common.date_required'),
        'debit_date.required' => __('common.date_required'),
    ])->validate();
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
    $invoice = new Invoice;
    $invoice->account()->associate($request->account);
    $invoice->description = $request->description;
    $invoice->date_init = $request->date_init;
    $invoice->date_end = $request->date_end;
    $invoice->debit_date = $request->debit_date;
    $invoice->save();
    return redirect('/account/'.$request->account->id.'/invoices/');    
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  Integer $id
   * @return \Illuminate\Http\Response
   */
  public function edit(Request $request)
  {
    return view('invoices.form', ['action'=>__('common.edit'),'account' => $request->account, 'invoice' => $request->invoice]);
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
    $request->invoice->description = $request->description;
    $request->invoice->date_init = $request->date_init;
    $request->invoice->date_end = $request->date_end;
    $request->invoice->debit_date = $request->debit_date;
    $request->invoice->save();
    return redirect('/account/'.$request->account->id.'/invoices');
  }

  public function confirm(Request $request){
    return view('invoices.confirm', ['account' => $request->account, 'invoice' => $request->invoice]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  Integer $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request)
  {
    $request->invoice->delete();
    return redirect('/account/'.$request->account->id.'/invoices');
  }
}
