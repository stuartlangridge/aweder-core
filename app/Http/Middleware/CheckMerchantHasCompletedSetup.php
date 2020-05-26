<?php

namespace App\Http\Middleware;

use App\Contract\Repositories\MerchantContract;
use App\Merchant;
use Closure;
use Illuminate\Auth\AuthManager;

class CheckMerchantHasCompletedSetup
{
    /**
     * @var AuthManager
     */
    protected $auth;

    /**
     * @var
     */
    protected $merchantRepository;

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
            return redirect()->route('register.business-details');
        }

        if ($this->doesMerchantHaveOpeningHours($merchant)) {
            $request->session()->flash('error', 'You must set your inventory before being able to take orders');

            return redirect()->route('registration.opening-hours');
        }

        if ($this->doesMerchantHaveNoCategoriesSet($merchant)) {
            $request->session()->flash('error', 'You must set your categories before being able to take orders');

            return redirect()->route('registration.categories');
        }

        if ($this->doesMerchantHaveNoInventory($merchant)) {
            $request->session()->flash('error', 'You must set your inventory before being able to take orders');

            return redirect()->route('admin.inventory');
        }

        return $next($request);
    }

    protected function doesMerchantHaveOpeningHours(Merchant $merchant): bool
    {
        return $merchant->openingHours->isEmpty();
    }

    /**
     * checks if the current merchant has categories set
     * @param Merchant $merchant
     * @return bool
     */
    protected function doesMerchantHaveNoCategoriesSet(Merchant $merchant): bool
    {
        return $merchant->categories->isEmpty();
    }

    /**
     * @param Merchant $merchant
     * @return bool
     */
    protected function doesMerchantHaveNoInventory(Merchant $merchant): bool
    {
        return $merchant->inventories->isEmpty();
    }
}
