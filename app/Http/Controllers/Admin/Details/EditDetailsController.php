<?php

namespace App\Http\Controllers\Admin\Details;

use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EditDetailsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param AuthManager $auth
     * @return Response
     */
    public function __invoke(Request $request, AuthManager $auth): Response
    {
        $merchant = $auth->user()->merchants->first();

        return response()->view('admin.details.edit', ['merchant' => $merchant, 'bodyClass' => 'body--auth']);
    }
}
