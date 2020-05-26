<?php

namespace App\Http\Controllers\Auth\Registration\MultiStep\BusinessAddress;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Details extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return response()->view('auth.registration.multi-step.business-address.details', ['bodyClass' => 'body--auth']);
    }
}
