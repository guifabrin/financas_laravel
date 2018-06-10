<?php

namespace App\Http\Middleware;

use Closure;

class CheckAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $accountId = $request->accountId;
        if (!$accountId || !($account = \Auth::user()->accounts->where('id',$accountId)->first())){
            return redirect('/accounts')->withErrors([__('accounts.not_your_account')]);
        }
        $request->account = $account;
        return $next($request);
    }
}
