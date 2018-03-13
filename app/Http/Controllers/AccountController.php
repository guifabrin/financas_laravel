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
    public function index()
    {
        $accounts = \Auth::user()->accounts()->paginate(10);
        return view('accounts.index', ['accounts' => $accounts]);
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
            'description' => 'required|min:5|max:50',
            'debit_day' => 'nullable|integer|between:1,31',
            'credit_close_day' => 'nullable|integer|between:1,31'
        ], [
            'description.required' => __('common.description_required'),
            'description.min' => __('common.description_min_5'),
            'description.max' => __('common.description_max_50'),
            'debit_day.between' => __('accounts.between_days'),
            'credit_close_day.between' => __('accounts.between_days')
        ])->after(function ($validator) use ($request){
            if ($request->is_credit_card) {
                if ($request->prefer_debit_account_id!=null){
                    $prefer_debit_account = \Auth::user()->accounts->where('id',$request->prefer_debit_account_id)->first();
                    if (!$prefer_debit_account){
                        if ($request->debit_day==null){
                            $validator->errors()->add('id', __('accounts.not_your_account'));
                        }
                    }
                }
                if ($request->debit_day==null){
                    $validator->errors()->add('debit_day', __('accounts.debit_day_null'));
                }

                if ($request->credit_close_day==null){
                    $validator->errors()->add('credit_close_day', __('accounts.credit_close_day_null'));
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
            
            $account->debit_day = $request->debit_day;
            $account->credit_close_day = $request->credit_close_day;   
        }
        $account->amount = 0;
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
            
            $account->debit_day = $request->debit_day;
            $account->credit_close_day = $request->credit_close_day;
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
