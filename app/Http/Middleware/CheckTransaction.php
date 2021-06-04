<?php

namespace App\Http\Middleware;

use Closure;

class CheckTransaction
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $transactionId = $request->transactionId;
        if (!$transactionId || !($transaction = $request->account->transactions->where('id', $transactionId)->first())) {
            return redirect('/account/' . $request->account->id . '/transactions')->withErrors([__('transactions.not_your_transaction')]);
        }
        $request->transaction = $transaction;
        return $next($request);
    }
}
