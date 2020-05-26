<?php

namespace App\Http\Middleware;

use App\Merchant;
use Closure;
use Illuminate\Auth\AuthManager;

class HasUserCompletedCurrentStage
{
    protected AuthManager $auth;

    public function __construct(AuthManager $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param int $stage
     * @return mixed
     */
    public function handle($request, Closure $next, int $stage)
    {
        $user = $this->auth->user();

        $merchant = $user->merchants->first();

        if ($merchant instanceof Merchant) {
            switch ($merchant->registration_stage) {
                case 1:
                    $request->session()->flash('error', 'You need to complete your business details');

                    return redirect()->route('register.business-details');
                    break;
                case 3:
                    if ($stage !== 3) {
                        $request->session()->flash('error', 'You need to complete your contact details');

                        return redirect()->route('register.contact-details');
                    }
                    break;
                case 4:
                    if ($stage !== 4) {
                        $request->session()->flash('error', 'You need to complete your business address');

                        return redirect()->route('register.business-address');
                    }
                    break;
                case 0:
                    if ($stage !== 0) {
                        return redirect()->route('admin.dashboard');
                    }
                    break;
            }
        }

        return $next($request);
    }
}
