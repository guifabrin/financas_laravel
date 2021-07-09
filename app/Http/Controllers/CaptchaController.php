<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class CaptchaController extends Controller
{
    public function index(Request $request)
    {
        return view_theme($request, 'captchas.index');
    }

    public function save(Request $request)
    {
        $sql = "
            UPDATE captcha SET result = ? WHERE id= ?;
        ";
        $params['captchas'] = DB::update($sql, [$request->value, $request->id]);
        return view_theme($request, 'captchas.index');
    }
}
