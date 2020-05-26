<?php

namespace App\Http\Controllers\Auth\Registration\MultiStep\BusinessDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Details extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request): Response
    {
        return response()->view('auth.registration.multi-step.business-details.details', ['bodyClass' => 'body--auth']);
    }
}
