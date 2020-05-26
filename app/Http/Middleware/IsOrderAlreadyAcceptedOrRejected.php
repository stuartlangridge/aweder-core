<?php

namespace App\Http\Middleware;

use Closure;

class IsOrderAlreadyAcceptedOrRejected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $order = $request->order;
        if ($order->status === 'acknowledged') {
            $request->session()->flash('error', 'This order has already been accepted');

            return redirect()->back();
        }

        if ($order->status === 'rejected') {
            $request->session()->flash(
                'error',
                'This order has already been rejected, the customer must create a new order'
            );

            return redirect()->back();
        }
        return $next($request);
    }
}
