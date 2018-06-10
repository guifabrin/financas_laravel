<?php

namespace App\Http\Middleware;

use Closure;

class CheckInvoice
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
        $invoiceId = $request->invoiceId;
        if (!$invoiceId || !($invoice = $request->account->invoices->where('id', $invoiceId)->first())){   
          return redirect('/account/'.$request->account->id.'/invoices')->withErrors([__('invoices.not_your_invoice')]);
        }
        $request->invoice = $invoice;
        return $next($request);
    }
}
