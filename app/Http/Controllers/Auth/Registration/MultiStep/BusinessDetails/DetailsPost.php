<?php

namespace App\Http\Controllers\Auth\Registration\MultiStep\BusinessDetails;

use App\Contract\Service\RegistrationContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\BusinessDetailsRequest;
use App\Merchant;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\RedirectResponse;

class DetailsPost extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param BusinessDetailsRequest $request
     * @param RegistrationContract $registrationService
     * @param AuthManager $auth
     * @return RedirectResponse
     */
    public function __invoke(
        BusinessDetailsRequest $request,
        RegistrationContract $registrationService,
        AuthManager $auth
    ): RedirectResponse {
        $user = $auth->user();

        $request = $request->merge(['registration_stage' => 3]);

        $merchant = $registrationService->createMerchantForUser($user, $request->except('_token'));

        $user->merchants()->attach($merchant->id);

        if ($merchant instanceof Merchant) {
            if ($request->hasFile('logo')) {
                $registrationService->uploadFileLogoForMerchant($request->file('logo'), $merchant);
            }
            $request->session()->put('success', 'Your merchant account is almost complete');

            return redirect()->route('register.contact-details');
        }

        return redirect()->back()->withInput();
    }
}
