<?php

namespace App\Http\Controllers\Admin\OpeningHours;

use App\Contract\Repositories\NormalOpeningHoursContract;
use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SetupController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param AuthManager $auth
     * @param NormalOpeningHoursContract $openingHoursRepo
     * @return Response
     */
    public function __invoke(
        Request $request,
        AuthManager $auth,
        NormalOpeningHoursContract $openingHoursRepo
    ): Response {
        $merchant = $auth->user()->merchants()->first();

        $openingHours = $openingHoursRepo->getOpeningHoursForMerchant($merchant->id);

        if ($openingHours->isEmpty()) {
            $openingHours = $openingHoursRepo->createDefaultOpeningHoursForMerchant($merchant->id);
        }

        $openingHours = $openingHoursRepo->formatOpeningHoursForForm($openingHours);

        return response()->view(
            'admin.openingHours.index',
            [
                'openingHours' => $openingHours,
                'bodyClass' => 'body--auth',
                'merchant' => $merchant,
            ]
        );
    }
}
