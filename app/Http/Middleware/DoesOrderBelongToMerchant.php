<?php

namespace App\Http\Middleware;

use App\Merchant;
use App\Order;
use App\Service\OrderService;
use App\User;
use Closure;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;

class DoesOrderBelongToMerchant
{
    /**
     * @var OrderService
     */
    protected $orderService;

    /**
     * @var AuthManager
     */
    protected $auth;

    /**
     * @var bool
     */
    protected $authenticatedUser;

    /**
     * DoesOrderBelongToMerchant constructor.
     * @param OrderService $orderService
     * @param AuthManager $auth
     */
    public function __construct(
        OrderService $orderService,
        AuthManager $auth
    ) {
        $this->orderService = $orderService;

        $this->auth = $auth;

        $this->authenticatedUser = false;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $merchant = $this->getMerchant($request);

        if ($merchant === null) {
            $request->session()->flash('error', 'No merchant exists');

            return redirect()->route('home');
        }

        $order = $request->order;

        if (!isset($order) || !$order instanceof Order) {
            $request->session()->flash('error', 'No order exists');

            return redirect()->route('home');
        }

        if (!$this->orderService->doesOrderBelongToMerchant($order, $merchant)) {
            $request->session()->flash('error', 'Oopps there appears to be a problem');

            if ($this->authenticatedUser === true) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('home');
        }

        return $next($request);
    }

    protected function getMerchant(Request $request): ?Merchant
    {
        if ($this->auth->user() instanceof  User) {
            $this->authenticatedUser = true;

            return $this->auth->user()->merchants->first();
        }

        if ($request->merchant instanceof Merchant) {
            return $request->merchant;
        }

        return null;
    }
}
