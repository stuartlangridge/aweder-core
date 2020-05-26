<?php

namespace App\Http\Middleware;

use App\Service\OrderService;
use Closure;

class HasOrderGonePastStageMiddleware
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $stage
     * @return mixed
     */
    public function handle($request, Closure $next, string $stage)
    {
        $order = $request->order;

        if ($this->orderService->hasOrderGonePastStage($order, $stage)) {
            $request->session()->flash('error', 'You\'ve already dealt with this');

            return redirect()->back();
        }

        return $next($request);
    }
}
