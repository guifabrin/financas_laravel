<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckInvoice
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
        $invoiceId = $request->invoiceId;
        if ($invoiceId) {
            $invoice = $request->account->invoices->where('id', $invoiceId)->first();
            if (!$invoice) {
                return redirect('/account/' . $request->account->id . '/invoices')->withErrors([__('invoices.not_your_invoice')]);
            }
            $request->invoice = $invoice;
        }
        return $next($request);
    }
}
