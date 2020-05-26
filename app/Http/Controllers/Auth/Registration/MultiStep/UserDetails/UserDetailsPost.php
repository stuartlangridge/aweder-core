<?php

namespace App\Http\Controllers\Auth\Registration\MultiStep\UserDetails;

use App\Contract\Service\RegistrationContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\UserDetailsRequest;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\RedirectResponse;

class UserDetailsPost extends Controller
{
    use RegistersUsers;

    /**
     * Handle the incoming request.
     *
     * @param UserDetailsRequest $request
     * @param RegistrationContract $registrationService
     * @return RedirectResponse
     */
    public function __invoke(
        UserDetailsRequest $request,
        RegistrationContract $registrationService
    ): RedirectResponse {
        $user = $registrationService->createNewUser($request->except('_token'));

        if ($user instanceof User) {
            $this->guard()->login($user);

            $user->sendEmailVerificationNotification();

            $request->session()->put('success', 'Your user account has been created');

            return redirect()->route('register.business-details');
        }

        return redirect()->back()->withInput();
    }
}
