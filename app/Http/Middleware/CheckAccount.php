<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAccount
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $accountId = $request->accountId;
        if ($accountId) {
            $account = $request->user()->accounts->where('id', $accountId)->first();
            if (!$account) {
                return redirect('/accounts')->withErrors([__('accounts.not_your_account')]);
            }
            $request->account = $account;
        }
        return $next($request);
    }
}
