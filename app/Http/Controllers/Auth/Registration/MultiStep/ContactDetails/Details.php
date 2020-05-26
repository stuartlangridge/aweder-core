<?php

namespace App\Http\Controllers\Auth\Registration\MultiStep\ContactDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Details extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        return response()->view('auth.registration.multi-step.contact-details.details', ['bodyClass' => 'body--auth']);
    }
}
