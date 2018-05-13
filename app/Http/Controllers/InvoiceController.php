<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Account;

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
}
