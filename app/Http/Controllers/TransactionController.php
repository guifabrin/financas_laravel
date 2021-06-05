<?php

namespace App\Http\Controllers;

use App\Category;
use App\CategoryTransaction;
use App\Http\Requests\UploadCsvRequest;
use App\Http\Requests\UploadOfxRequest;
use App\Invoice;
use App\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->year && $request->month) {
            $dateInit = date("Y-m-d", strtotime(date($request->year . '-' . ($request->month) . '-1')));
            $dateEnd = date('Y-m-t', strtotime($dateInit));
        } else {
            $dateInit = $request->date_init;
            $dateEnd = $request->date_end;
        }
        $filterDate = true;
        if (isset($request->invoice_id)) {
            $invoice = $request->account->invoices()->where('id', $request->invoice_id)->first();
            if (isset($invoice)) {
                $filterDate = false;
                $transactions = $invoice->transactions();
            } else {
                $transactions = $request->account->transactions();
            }
        } else if (isset($request->account)) {
            $transactions = $request->account->transactions();
        } else {
            $transactions = Transaction::whereIn('account_id', $request->user()->accounts->map(function ($account) {
                return $account->id;
            }));
        }
        if ($filterDate && $dateInit !== null && $dateEnd !== null) {
            $transactions->whereBetween('date', [$dateInit, $dateEnd]);
        }
        $transactions = $transactions->whereRaw("lower(description) LIKE '%" . strtolower($request->description) . "%'")->orderBy('date')->orderBy('description')->paginate(30)->appends(request()->input());
        return view('transactions.index', ['account' => $request->account, 'transactions' => $transactions, 'dateInit' => $dateInit, 'dateEnd' => $dateEnd]);
    }

    public function create(Request $request)
    {
        return view('transactions.form', ['action' => __('common.add'), 'account' => $request->account]);
    }

    public function store(Request $request)
    {
        $invoiceId = null;
        if ($request->invoice_id == -1) {
            $invoice = new Invoice;
            $invoice->account()->associate($request->account);
            $invoice->description = $request->invoice_description;
            $invoice->date_init = $request->invoice_date_init;
            $invoice->date_end = $request->invoice_date_end;
            $invoice->debit_date = $request->invoice_debit_date;
            $invoice->save();
            $invoiceId = $invoice->id;
        } else if ($request->invoice_id != null) {
            $invoiceId = $request->invoice_id;
        }
        $transaction = new Transaction;
        $transaction->account()->associate($request->account);
        $transaction->date = $request->date;
        $transaction->description = $request->description;
        $transaction->value = $request->value;
        $transaction->paid = isset($request->paid) ? $request->paid : false;
        $transaction->invoice_id = $invoiceId;
        $transaction->save();
        foreach ($transaction->categories as $categoryTransaction) {
            $categoryTransaction->delete();
        }
        $categoriesString = explode(',', $request->categories);
        foreach ($categoriesString as $categoryString) {
            $category = Category::where(['user_id' => $request->user()->id, 'description' => $categoryString])->first();
            if (!isset($category)) {
                $category = new Category;
                $category->user_id = $request->user()->id;
                $category->description = $categoryString;
                $category->save();
            }
            $categoryTransaction = new CategoryTransaction;
            $categoryTransaction->category()->associate($category->id);
            $categoryTransaction->transaction()->associate($transaction->id);
            $categoryTransaction->save();
        }
        return view('layouts.reload');
    }

    public function edit(Request $request)
    {
        return view('transactions.form', ['action' => __('common.edit'), 'account' => $request->account, 'transaction' => $request->transaction]);
    }

    public function update(Request $request)
    {
        $paid = isset($request->paid) ? $request->paid : false;
        $invoiceId = null;
        if ($request->invoice_id == -1) {
            $invoice = new Invoice;
            $invoice->account()->associate($request->account);
            $invoice->description = $request->invoice_description;
            $invoice->date_init = $request->invoice_date_init;
            $invoice->date_end = $request->invoice_date_end;
            $invoice->debit_date = $request->invoice_debit_date;
            $invoice->save();
            $invoiceId = $invoice->id;
        } else if ($request->invoice_id != null) {
            $invoiceId = $request->invoice_id;
        }
        $request->transaction->date = $request->date;
        $request->transaction->description = $request->description;
        $request->transaction->value = $request->value;
        $request->transaction->paid = $paid;
        $request->transaction->invoice_id = $invoiceId;
        foreach ($request->transaction->categories as $categoryTransaction) {
            $categoryTransaction->delete();
        }
        $categoriesString = explode(',', $request->categories);
        foreach ($categoriesString as $categoryString) {
            $category = Category::where(['user_id' => $request->user()->id, 'description' => $categoryString])->first();
            if (!isset($category)) {
                $category = new Category;
                $category->user_id = $request->user()->id;
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
        return view('layouts.reload');
    }

    public function confirm(Request $request)
    {
        return view('transactions.confirm', ['account' => $request->account, 'transaction' => $request->transaction]);
    }

    public function destroy(Request $request)
    {
        $request->transaction->delete();
        return view('layouts.reload');
    }

    public function uploadOfx(UploadOfxRequest $request)
    {
        $accountId = $request->accountId;
        if (!$accountId || !($account = $request->user()->accounts->where('id', $accountId)->first())) {
            return redirect('/accounts')->withErrors([__('accounts.not_your_account')]);
        }
        foreach ($request->file('ofx-file') as $file) {
            $xmlstr = $this->getXMLPartOfOFX(file_get_contents($file));
            $xml = simplexml_load_string(utf8_encode($xmlstr));
            $ofx = new \OfxParser\Ofx($xml);
            $bankAccount = reset($ofx->bankAccounts);
            $startDate = $bankAccount->statement->startDate;
            $endDate = $bankAccount->statement->endDate;
            $invoiceId = null;
            if (!isset($invoiceId) && $account->is_credit_card) {
                $invoice = new Invoice;
                $invoice->account()->associate($account);
                $invoice->description = "Invoice " . $file->getClientOriginalName();
                $invoice->date_init = date("Y-m-d\TH:i:s", $startDate->getTimestamp());
                $invoice->date_end = date("Y-m-d\TH:i:s", $endDate->getTimestamp());
                $invoice->debit_date = new \DateTime();
                $invoice->save();
                $invoiceId = $invoice->id;
            }
            $transactions = $bankAccount->statement->transactions;
            foreach ($transactions as $ofxTransaction) {
                $transaction = new Transaction;
                $transaction->date = date("Y-m-d\TH:i:s", $ofxTransaction->date->getTimestamp());
                $transaction->description = $ofxTransaction->memo;
                $transaction->value = $ofxTransaction->amount;
                $transaction->paid = true;
                $transaction->account_id = $account->id;
                if ($account->is_credit_card) {
                    $transaction->invoice_id = $invoiceId;
                }
                $transaction->save();
            }
        }
        return redirect('/accounts/');
    }

    function getXMLPartOfOFX($string, $start = '<OFX>', $end = '</OFX>')
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return $start . substr($string, $ini, $len) . $end;
    }

    public function uploadCsv(UploadCsvRequest $request)
    {
        $accountId = $request->accountId;
        if (!$accountId || !($account = $request->user()->accounts->where('id', $accountId)->first())) {
            return redirect('/accounts')->withErrors([__('accounts.not_your_account')]);
        }
        $invoiceId = $request->invoiceId;
        $invoice = $account->invoices->where('id', $invoiceId)->first();
        $clearInvoice = false;
        foreach ($request->file('csv-file') as $file) {
            $csvData = $this->csvToArray($file);
            $invoiceId = isset($invoice) ? $invoice->id : null;
            if (!isset($invoiceId) && $account->is_credit_card) {
                $invoice = new Invoice;
                $invoice->account()->associate($account);
                $invoice->description = "Invoice " . $file->getClientOriginalName();
                $invoice->date_init = date("Y-m-d\TH:i:s", strtotime($csvData[0]["date"]));
                $invoice->date_end = date("Y-m-d\TH:i:s", strtotime($csvData[count($csvData) - 1]["date"]));
                $invoice->debit_date = new \DateTime();
                $invoice->save();
                $invoiceId = $invoice->id;
                $clearInvoice = true;
            }
            foreach ($csvData as $csvTransaction) {
                $transaction = new Transaction;
                $transaction->date = date("Y-m-d\TH:i:s", strtotime($csvTransaction["date"]));
                $transaction->description = $csvTransaction["description"];
                $transaction->value = $csvTransaction["value"] * 1;
                $transaction->paid = true;
                $transaction->account_id = $account->id;
                if ($account->is_credit_card) {
                    $transaction->invoice_id = $invoiceId;
                }
                $transaction->save();
            }
            if ($clearInvoice) {
                $invoice = null;
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
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        return $data;
    }

    public function repeat(Request $request)
    {
        return view('transactions.repeat', [
            'account' => $request->account,
            'transaction' => $request->transaction
        ]);
    }

    public function confirmRepeat(Request $request)
    {
        $request->account->save();
        for ($i = 0; $i < $request->times; $i++) {
            $transaction = new Transaction;
            $transaction->date = date("Y-m-d\TH:i:s", strtotime("+" . ($i + 1) . " month", strtotime($request->transaction->date)));
            $transaction->description = $request->transaction->description;
            $transaction->value = $request->transaction->value;
            $transaction->paid = false;
            $transaction->account_id = $request->account->id;
            if ($request->account->is_credit_card) {
                $transaction->invoice_id = $request->transaction->invoice_id;
            }
            $transaction->save();
        }
        return view('layouts.reload');
    }

    public function addCategories(Request $request)
    {
        $categoriesString = explode(',', $request->categories);
        foreach ($categoriesString as $categoryString) {
            $category = Category::where(['user_id' => $request->user()->id, 'description' => $categoryString])->first();
            if (!isset($category)) {
                $category = new Category;
                $category->user_id = $request->user()->id;
                $category->description = $categoryString;
                $category->save();
            }
            if ($request->year && $request->month) {
                $dateInit = date("Y-m-d", strtotime(date($request->year . '-' . ($request->month) . '-1')));
                $dateEnd = date('Y-m-t', strtotime($dateInit));
            } else {
                $dateInit = $request->date_init;
                $dateEnd = $request->date_end;
            }
            $filterDate = true;
            if (isset($request->invoice_id)) {
                $invoice = $request->account->invoices()->where('id', $request->invoice_id)->first();
                if (isset($invoice)) {
                    $filterDate = false;
                    $transactions = $invoice->transactions();
                } else {
                    $transactions = $request->account->transactions();
                }
            } else if (isset($request->account)) {
                $transactions = $request->account->transactions();
            } else {
                $transactions = Transaction::whereIn('account_id', $request->user()->accounts->map(function ($account) {
                    return $account->id;
                }));
            }
            if ($filterDate && $dateInit !== null && $dateEnd !== null) {
                $transactions->whereBetween('date', [$dateInit, $dateEnd]);
            }
            $transactions = $transactions->whereRaw("lower(description) LIKE '%" . strtolower($request->description) . "%'")->get();
            foreach ($transactions as $transaction) {
                if ($transaction->categories->where('category_id', $category->id)->first() === null) {
                    $categoryTransaction = new CategoryTransaction;
                    $categoryTransaction->category()->associate($category->id);
                    $categoryTransaction->transaction()->associate($transaction);
                    $categoryTransaction->save();
                }
            }
        }

        return redirect((isset($request->account) ? '/account/' . $request->account->id : '') . '/transactions');
    }
}
