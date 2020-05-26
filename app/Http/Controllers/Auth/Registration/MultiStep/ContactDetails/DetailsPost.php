<?php

namespace App\Http\Controllers\Auth\Registration\MultiStep\ContactDetails;

use App\Contract\Service\RegistrationContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\ContactDetailsRequest;
use Illuminate\Http\RedirectResponse;

class DetailsPost extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param ContactDetailsRequest $request
     * @param RegistrationContract $registrationService
     *
     * @return RedirectResponse
     */
    public function __invoke(
        ContactDetailsRequest $request,
        RegistrationContract $registrationService
    ): RedirectResponse {
        $request = $request->merge(['registration_stage' => 4]);

        $merchant = $request->user()->merchants->first();

        $updatedMerchant = $registrationService->updateMerchant($merchant, $request->except('_token'));

        if ($updatedMerchant === true) {
            $request->session()->put('success', 'Your merchant account is almost complete');

            return redirect()->route('register.business-address');
        }

        return redirect()->back()->withInput();
    }
}
