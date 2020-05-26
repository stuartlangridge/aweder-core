<?php

namespace App\Http\Controllers\Auth\Registration\MultiStep\UserDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserDetails extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return response()->view('auth.registration.multi-step.user-details', ['bodyClass' => 'body--auth']);
    }
}
