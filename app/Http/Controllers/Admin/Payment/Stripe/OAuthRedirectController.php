<?php

namespace App\Http\Controllers\Admin\Payment\Stripe;

use App\Contract\Service\StripeContract;
use App\Http\Controllers\Controller;
use App\Provider;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Stripe\Exception\OAuth\OAuthErrorException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class OAuthRedirectController
 * @package App\Http\Controllers\Admin\Payment
 */
class OAuthRedirectController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param AuthManager $auth
     * @param StripeContract $stripeService
     * @param Provider $providerModel
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(
        Request $request,
        AuthManager $auth,
        StripeContract $stripeService,
        Provider $providerModel
    ): Response {
        if ($request->has('error')) {
            $request->session()->flash('error', 'There was an error authenticating please try again');

            return redirect()->route('admin.details.edit');
        }

        $returnedState = $request->get('state');

        $merchant = $auth->user()->merchants()->first();

        $verified = $stripeService->verifyUserStateForStripe(
            $returnedState,
            $merchant->id,
            $merchant->url_slug,
            $merchant->salt
        );

        if (!$verified) {
            $request->session()->flash('error', 'Incorrect state parameter: ' . $returnedState);

            return redirect()->route('admin.details.edit');
        }

        $code = $request->get('code');

        try {
            $stripeResponse = $stripeService->getOAuthTokenFromStripeWithCode($code);
        } catch (OAuthErrorException $e) {
            $request->session()->flash('error', 'Invalid authorization code: ' . $code);

            return redirect()->route('admin.details.edit');
        } catch (\Exception $e) {
            $request->session()->flash('error', 'An unknown error occurred.');

            return redirect()->route('admin.details.edit');
        }

        $stripeProvider = $providerModel->where('name', 'Stripe')->first();

        if ($stripeProvider === null) {
            $request->session()->flash('error', 'No Stripe provider could be found');

            return redirect()->route('admin.details.edit');
        }

        $merchant->paymentProviders()->detach($stripeProvider->id);

        $merchant->paymentProviders()->attach($stripeProvider->id, ['data' => json_encode($stripeResponse)]);

        $request->session()->flash('success', 'Stripe has been added to your account');

        return redirect()->route('admin.details.edit');
    }
}
