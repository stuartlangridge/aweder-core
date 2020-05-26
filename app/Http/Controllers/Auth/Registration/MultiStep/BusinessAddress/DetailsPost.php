<?php

namespace App\Http\Controllers\Auth\Registration\MultiStep\BusinessAddress;

use App\Contract\Service\EmailServiceContract;
use App\Contract\Service\RegistrationContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\BusinessAddressRequest;
use App\Mail\MerchantSignUp;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\RedirectResponse;

class DetailsPost extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param BusinessAddressRequest $request
     * @param RegistrationContract $registrationService
     * @param AuthManager $auth
     * @param EmailServiceContract $emailService
     * @return RedirectResponse
     * @todo Add Email to team on registration complete
     */
    public function __invoke(
        BusinessAddressRequest $request,
        RegistrationContract $registrationService,
        AuthManager $auth,
        EmailServiceContract $emailService
    ): RedirectResponse {
        $request = $request->merge(['registration_stage' => 0]);

        $merchant = $request->user()->merchants->first();

        $updatedMerchant = $registrationService->updateMerchant($merchant, $request->except('_token'));

        if ($updatedMerchant === true) {
            $emailService->setToAddress(config('mail.to.register_interest'))->sendEmail(new MerchantSignUp($merchant));
            $request->session()->put('success', 'Your merchant account is almost complete');

            return redirect()->route('registration.opening-hours');
        }


        return redirect()->back()->withInput();
    }
}
