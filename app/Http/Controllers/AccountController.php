<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Accounts;

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
        $accounts = \Auth::user()->accounts;
        return view('accounts.index', ['accounts' => $accounts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $accounts = \Auth::user()->accounts;
        $selectAccounts = [[null=>'None']];
        foreach($accounts as $account){
            if (!$account->is_credit_card){
                $selectAccounts[$account->id] = $account->description;
            }
        }
        return view('accounts.form', ['selectAccounts' => $selectAccounts]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'description' => 'required|min:5|max:50',
            'debit_day' => 'nullable|   min:1|max:31'
        ])->after(function ($validator) use ($request){
            if ($request->is_credit_card) {
                if ($request->prefer_debit_account_id==null){
                    $validator->errors()->add('prefer_debit_account_id', 'prefer_debit_account_id null');
                } else {
                    $prefer_debit_account = Accounts::find($request->prefer_debit_account_id);
                    if (
                        $prefer_debit_account->user->id!=\Auth::user()->id){
                        if ($request->debit_day==null){
                            $validator->errors()->add('id', 'user_id diff');
                        }
                    }
                }
                if ($request->debit_day==null){
                    $validator->errors()->add('debit_day', 'debit_day null');
                }

                if ($request->credit_close_day==null){
                    $validator->errors()->add('credit_close_day', 'credit_close_day null');
                }
            }
        })->validate();

        $account = new Accounts;

        $account->description = $request->description;
        $account->user()->associate(\Auth::user());
        
        $account->is_credit_card = $request->is_credit_card==null?false:$request->is_credit_card;
        $prefer_debit_account = Accounts::find($request->prefer_debit_account_id);
        if ($prefer_debit_account){
            $account->preferDebitAccount()->associate($prefer_debit_account);
        }
        
        $account->debit_day = $request->debit_day;
        $account->credit_close_day = $request->credit_close_day;
        

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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
