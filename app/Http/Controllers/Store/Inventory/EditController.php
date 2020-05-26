<?php

namespace App\Http\Controllers\Store\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

/**
 * Class EditController
 * @package App\Http\Controllers\Store\Inventory
 */
class EditController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @todo I DONT THNK THIS IS BEING USED
     */
    public function __invoke(Request $request): Response
    {
        return response()->view('home');
    }
}
