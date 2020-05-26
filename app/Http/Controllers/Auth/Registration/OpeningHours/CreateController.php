<?php

namespace App\Http\Controllers\Auth\Registration\OpeningHours;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Contract\Repositories\NormalOpeningHoursContract;

class CreateController extends Controller
{

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param NormalOpeningHoursContract $repository
     * @return RedirectResponse
     */
    public function __invoke(Request $request, NormalOpeningHoursContract $repository): RedirectResponse
    {
        $days = $request->except('_token');

        $merchant = $request->user()->merchants->first();

        $repository->clearCurrentOpeningHoursForMerchant($merchant->id);

        if ($repository->createNormalOpeningHours($days, $merchant->id)) {
            $request->session()->flash('success', 'Your opening hours have been set');

            return redirect()->route('registration.categories');
        }

        $request->session()->flash('error', 'There was an error setting your opening hours please try again');

        return redirect()->back()->withInput();
    }
}
