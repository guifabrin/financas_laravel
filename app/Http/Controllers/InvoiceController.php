<?php

namespace App\Http\Controllers;

use App\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $invoices = $request->account->invoices()->orderBy('debit_date')->get();
        return view('invoices.index', ['account' => $request->account, 'invoices' => $invoices]);
    }

    public function create(Request $request)
    {
        return view('invoices.form', ['action' => __('common.add'), 'account' => $request->account]);
    }

    public function store(Request $request)
    {
        $invoice = new Invoice;
        $invoice->account()->associate($request->account);
        $invoice->description = $request->description;
        $invoice->date_init = $request->date_init;
        $invoice->date_end = $request->date_end;
        $invoice->debit_date = $request->debit_date;
        $invoice->save();
        return view('layouts.close_modal');
    }

    public function edit(Request $request)
    {
        return view('invoices.form', ['action' => __('common.edit'), 'account' => $request->account, 'invoice' => $request->invoice]);
    }

    public function update(Request $request)
    {
        $request->invoice->description = $request->description;
        $request->invoice->date_init = $request->date_init;
        $request->invoice->date_end = $request->date_end;
        $request->invoice->debit_date = $request->debit_date;
        $request->invoice->save();
        return view('layouts.close_modal');
    }

    public function destroy(Request $request)
    {
        $request->invoice->transactions()->delete();
        $request->invoice->delete();
        return redirect('/account/' . $request->account->id . '/invoices');
    }
}
