<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\Invoice;
use App\Category;
use App\CategoryTransaction;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests\UploadOfxRequest;
use App\Http\Requests\UploadCsvRequest;
use Illuminate\Support\Facades\Input;

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

    private function getQuery(){
      $dateInit = Input::get('date_init');
      $dateEnd = Input::get('date_end');
      $description = Input::get('description');
      return implode(['description='.$description, (isset($dateInit) && isset($dateEnd) ? 'date_init='.$dateInit.'&date_end='.$dateEnd : '')], '&');
    }

    private function getEloquentTransactions($request){
      $date_init = $request->input('date_init');
      $date_end = $request->input('date_end');
      $filter_date = true;
      if (isset($request->invoice_id)){
        $invoice = $request->account->invoices()->where('id', $request->invoice_id)->first();
        if (isset($invoice)){
          $filter_date = false;
          $transactions = $invoice->transactions();
        } else {
          $transactions =  $request->account->transactions();
        }
      } else {
        if (isset($request->account)){
          $transactions =  $request->account->transactions();
        } else {
          $transactions = Transaction::whereIn('account_id', \Auth::user()->accounts->map(function ($account) {
              return $account->id;
          }));
        }
      }      
      if ($filter_date){    
        if ($date_init!==null && $date_end!==null){
          $transactions->whereBetween('date',[$date_init, $date_end]);
        }
      }

      $request->description = iconv('UTF-8','ASCII//TRANSLIT', strtolower($request->description));
      $transactions = $transactions->whereRaw("replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace( lower(description), 'á','a'), 'ã','a'), 'â','a'), 'é','e'), 'ê','e'), 'í','i'),'ó','o') ,'õ','o') ,'ô','o'),'ú','u'), 'ç','c') LIKE '%{$request->description}%'")->orderBy('date')->orderBy('description');
      return $transactions;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $transactions = $this->getEloquentTransactions($request)->paginate(30)->appends(request()->input());
      return view('transactions.index', ['account' => $request->account, 'transactions' => $transactions]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function charts(Request $request)
    {
      $transactions = $this->getEloquentTransactions($request)->get();
      $categories = \Auth::user()->categories;
      $category_transactions = [];
      $transactions->each(function ($transaction) use (&$category_transactions){
        $transaction->categories->each(function ($category) use (&$category_transactions){
          $category_transactions[] = $category;
        });
      });
      return view('transactions.charts', ['account' => $request->account, 'transactions' => $transactions, 'categories'=>$categories, 'category_transactions'=>$category_transactions]);
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
      $invoiceId = null;
      if ($request->invoice_id==-1){
        $invoice = new Invoice;
        $invoice->account()->associate($request->account);
        $invoice->description = $request->invoice_description;
        $invoice->date_init = $request->invoice_date_init;
        $invoice->date_end = $request->invoice_date_end;
        $invoice->debit_date = $request->invoice_debit_date;
        $invoice->save();
        $invoiceId = $invoice->id;
      } else if ($request->invoice_id!=null){
        $invoiceId = $request->invoice_id;    
      }
      $transaction = new Transaction;
      $transaction->account()->associate($request->account);
      $transaction->date = $request->date;
      $transaction->description =$request->description;
      $transaction->value = $request->value;
      $transaction->paid = isset($request->paid)?$request->paid:false;
      $transaction->invoice_id = $invoiceId;
      $transaction->save();
       foreach($transaction->categories as $categoryTransaction){
        $categoryTransaction->delete();
      }
      $categoriesString = explode(',', $request->categories);
      foreach ($categoriesString as $categoryString){
        $category = Category::where(['user_id'=>\Auth::user()->id, 'description' => $categoryString])->first();
        if (!isset($category)){
          $category = new Category;
          $category->user_id = \Auth::user()->id;
          $category->description = $categoryString;
          $category->save();
        }
        $categoryTransaction = new CategoryTransaction;
        $categoryTransaction->category()->associate($category->id);
        $categoryTransaction->transaction()->associate($transaction->id);
        $categoryTransaction->save();
      }
      return redirect('/account/'.$request->account->id.'/transactions?'. $this->getQuery());    
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
      $paid = isset($request->paid)?$request->paid:false;
      $invoiceId = null;
      if ($request->invoice_id==-1){
        $invoice = new Invoice;
        $invoice->account()->associate($request->account);
        $invoice->description = $request->invoice_description;
        $invoice->date_init = $request->invoice_date_init;
        $invoice->date_end = $request->invoice_date_end;
        $invoice->debit_date = $request->invoice_debit_date;
        $invoice->save();
        $invoiceId = $invoice->id;
      } else if ($request->invoice_id!=null){
        $invoiceId = $request->invoice_id;    
      }
      $request->transaction->date = $request->date;
      $request->transaction->description =$request->description;
      $request->transaction->value = $request->value;
      $request->transaction->paid = $paid;
      $request->transaction->invoice_id = $invoiceId;
      foreach($request->transaction->categories as $categoryTransaction){
        $categoryTransaction->delete();
      }
      $categoriesString = explode(',', $request->categories);
      foreach ($categoriesString as $categoryString){
        $category = Category::where(['user_id'=>\Auth::user()->id, 'description' => $categoryString])->first();
        if (!isset($category)){
          $category = new Category;
          $category->user_id = \Auth::user()->id;
          $category->description = $categoryString;
          $category->save();
        }
        $categoryTransaction = new CategoryTransaction;
        $categoryTransaction->category()->associate($category->id);
        $categoryTransaction->transaction()->associate($request->transaction->id);
        $categoryTransaction->save();
      }
      $request->transaction->save();
      $request->account->save();
      return redirect('/account/'.$request->account->id.'/transactions?'.$this->getQuery());
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
      $accountId = $request->accountId;
      if (!$accountId || !($account = \Auth::user()->accounts->where('id',$accountId)->first())){
        return redirect('/accounts')->withErrors([__('accounts.not_your_account')]);
      }
      $invoiceId = $request->invoiceId;
      $invoice = $account->invoices->where('id', $invoiceId)->first();
      foreach ($request->file('ofx-file') as $file) {
        $xmlstr = $this->getOfxAsXML($file);
        $ofxParser = new \OfxParser\Parser();
        $xml = simplexml_load_string($xmlstr);
        $ofx = new \OfxParser\Ofx($xml);
        $bankAccount = reset($ofx->bankAccounts);
        $startDate = $bankAccount->statement->startDate;
        $endDate = $bankAccount->statement->endDate;
        $invoiceId = null;
        if (!isset($invoiceId) && $account->is_credit_card){
          $invoice = new Invoice;
          $invoice->account()->associate($account);
          $invoice->description = "Invoice ".$file->getClientOriginalName();
          $invoice->date_init = date("Y-m-d\TH:i:s", $startDate->getTimestamp());
          $invoice->date_end = date("Y-m-d\TH:i:s", $endDate->getTimestamp());
          $invoice->debit_date = new \DateTime();
          $invoice->save();
          $invoiceId = $invoice->id; 
        }
        $transactions = $bankAccount->statement->transactions;
        foreach($transactions as $ofxTransaction){
          $transaction = new Transaction;
          $transaction->date = date("Y-m-d\TH:i:s", $ofxTransaction->date->getTimestamp());
          $transaction->description = $ofxTransaction->memo;
          $transaction->value = $ofxTransaction->amount;
          $transaction->paid = true;
          $transaction->account_id = $account->id;
          if ($account->is_credit_card){
            $transaction->invoice_id = $invoiceId;
          }
          $transaction->save();
        }
      }
      return redirect('/accounts/');
    }

    private function csvToArray($filename = '', $delimiter = ',')
    {
      if (!file_exists($filename) || !is_readable($filename))
        return false;
      $header = null;
      $data = array();
      if (($handle = fopen($filename, 'r')) !== false)
      {
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
        {
          if (!$header)
            $header = $row;
          else
            $data[] = array_combine($header, $row);
        }
        fclose($handle);
      }
      return $data;
    }

    public function uploadCsv(UploadCsvRequest $request)
    {
      $accountId = $request->accountId;
      if (!$accountId || !($account = \Auth::user()->accounts->where('id',$accountId)->first())){
          return redirect('/accounts')->withErrors([__('accounts.not_your_account')]);
      }
      $invoiceId = $request->invoiceId;
      $invoice = $account->invoices->where('id', $invoiceId)->first();
      $clearInvoice = false;
      foreach ($request->file('csv-file') as $file) {
        $csvData = $this->csvToArray($file);
        $invoiceId = isset($invoice) ? $invoice->id : null;
        if (!isset($invoiceId) && $account->is_credit_card){
          $invoice = new Invoice;
          $invoice->account()->associate($account);
          $invoice->description = "Invoice ".$file->getClientOriginalName();
          $invoice->date_init = date("Y-m-d\TH:i:s", strtotime($csvData[0]["date"]));
          $invoice->date_end = date("Y-m-d\TH:i:s", strtotime($csvData[count($csvData)-1]["date"]));
          $invoice->debit_date = new \DateTime();
          $invoice->save();
          $invoiceId = $invoice->id;
          $clearInvoice = true;
        }
        foreach($csvData as $csvTransaction){
          $transaction = new Transaction;
          $transaction->date = date("Y-m-d\TH:i:s", strtotime($csvTransaction["date"]));
          $transaction->description = $csvTransaction["description"];
          $transaction->value = $csvTransaction["value"]*1;
          $transaction->paid = true;
          $transaction->account_id = $account->id;
          if ($account->is_credit_card){
            $transaction->invoice_id = $invoiceId;
          }
          $transaction->save();
        }
        if ($clearInvoice){
          $invoice = null;
        }
      }
      return redirect('/accounts/');
    }

    public function repeat(Request $request){
      return view('transactions.repeat', ['account' => $request->account, 'transaction' => $request->transaction]);
    }

    public function confirmRepeat(Request $request){
      $request->account->save();
      for ($i=0; $i<$request->times; $i++){
        $transaction = new Transaction;
        $transaction->date = date("Y-m-d\TH:i:s", strtotime("+".($i+1)." month", strtotime($request->transaction->date)));
        $transaction->description = $request->transaction->description;
        $transaction->value = $request->transaction->value;
        $transaction->paid = false;
        $transaction->account_id = $request->account->id;
        if ($request->account->is_credit_card){
          $transaction->invoice_id = $request->transaction->invoice_id;
        }
        $transaction->save();
      }
      return redirect('/account/'.$request->account->id.'/transactions?'.$this->getQuery());
    }

    public function addCategories(Request $request){
      $categoriesString = explode(',', $request->categories);
      foreach ($categoriesString as $categoryString){
        $category = Category::where(['user_id'=>\Auth::user()->id, 'description' => $categoryString])->first();
        if (!isset($category)){
          $category = new Category;
          $category->user_id = \Auth::user()->id;
          $category->description = $categoryString;
          $category->save();
        }

        $transactions = $this->getEloquentTransactions($request)->get();
        foreach($transactions as $transaction){
          if ($transaction->categories->where('category_id', $category->id)->first() === null) {
            $categoryTransaction = new CategoryTransaction;
            $categoryTransaction->category()->associate($category->id);
            $categoryTransaction->transaction()->associate($transaction);
            $categoryTransaction->save();
          }
        }
      }

      return redirect((isset($request->account)?'/account/'.$request->account->id:'').'/transactions?'.$this->getQuery());
    }
}
