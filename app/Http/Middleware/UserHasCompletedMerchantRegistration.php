<?php

namespace App\Http\Middleware;

use App\Contract\Repositories\MerchantContract;
use Closure;
use Illuminate\Auth\AuthManager;

class UserHasCompletedMerchantRegistration
{
    protected AuthManager $auth;

    protected MerchantContract $merchantRepository;

    public function __construct(AuthManager $auth, MerchantContract $merchantRepository)
    {
        $this->auth = $auth;

        $this->merchantRepository = $merchantRepository;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $this->auth->user();

        $merchant = $user->merchants->first();

        if ($user->merchants->count() === 0) {
            $request->session()->flash('error', 'You need to complete your merchant registration');

            return redirect()->route('register.business-details');
        }

        if ($merchant->registration_stage !== 0) {
            switch ($merchant->registration_stage) {
                case 1:
                    $request->session()->flash('error', 'You need to complete your business details');

                    return redirect()->route('register.business-details');
                    break;
                case 3:
                    $request->session()->flash('error', 'You need to complete your contact details');

                    return redirect()->route('register.contact-details');
                    break;
                case 4:
                    $request->session()->flash('error', 'You need to complete your business address');

                    return  redirect()->route('register.business-address');
                    break;
            }
        }


        return $next($request);
    }
}
