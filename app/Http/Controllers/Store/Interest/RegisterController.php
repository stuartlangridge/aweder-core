<?php

namespace App\Http\Controllers\Store\Interest;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterInterestRequest;
use App\Mail\RegisterInterest;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    /**
     * handles the submission of the signup form
     * @param RegisterInterestRequest $request
     */
    public function __invoke(RegisterInterestRequest $request)
    {
        $message = (
            new RegisterInterest(
                [
                    'business' => $request->input('business'),
                    'email' => $request->input('email'),
                    'business_type' => $request->input('business_type'),
                    'location' => $request->input('location')
                ]
            )
        )->onConnection(config('queue.default'))
            ->onQueue(config('queues.register_interest'));

        Mail::to(config('mail.to.register_interest'))
            ->cc(config('mail.cc.register_interest'))
            ->bcc(config('mail.bcc.register_interest'))
            ->queue($message);

        return redirect()->route('register.thanks');
    }
}
