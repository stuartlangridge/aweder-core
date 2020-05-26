<?php

namespace App\Http\Controllers\Admin\Payment\Stripe;

use App\Contract\Service\StripeContract;
use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Stripe\Exception\OAuth\OAuthErrorException;

class DeauthorizeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param AuthManager $auth
     * @param StripeContract $stripeService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request, AuthManager $auth, StripeContract $stripeService): RedirectResponse
    {
        $merchant = $auth->user()->merchants()->first();

        if (!$merchant->hasStripePaymentsIntegration()) {
            $request->session()->flash('error', 'You don\'t have stripe authorised on your account');

            return redirect()->route('admin.details.edit');
        }

        $stripeDetails = $merchant->stripePayment();

        $stripeAuthData = json_decode($stripeDetails->pivot->data, true);

        try {
            $stripeResponse = $stripeService->deAuthorizeStripe(
                $stripeService->getClientId(),
                $stripeAuthData['stripe_user_id']
            );

            $merchant->paymentProviders()->detach($stripeDetails->id);
        } catch (OAuthErrorException $e) {
            $request->session()->flash('error', 'There was an error with the action please try again');

            return redirect()->route('admin.details.edit');
        } catch (\Exception $e) {
            $request->session()->flash('error', 'There was an error with the action please try again');

            return redirect()->route('admin.details.edit');
        }

        $request->session()->flash('success', 'Stripe has been added to your account');

        return redirect()->route('admin.details.edit');
    }
}
