<?php

namespace App\Http\Controllers\Auth\Registration\OpeningHours;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SetupController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        return response()->view('auth.registration.openingHours.index', ['bodyClass' => 'body--auth']);
    }
}
