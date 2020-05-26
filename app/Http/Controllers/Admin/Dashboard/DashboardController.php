<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Contract\Repositories\OrderContract;
use App\Contract\Service\SearchOrderContract;
use App\Http\Controllers\Controller;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DashboardController extends Controller
{
    use HelperTrait;

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param OrderContract $repository
     * @param SearchOrderContract $searchOrderContract
     * @return Response
     */
    public function __invoke(
        Request $request,
        OrderContract $repository,
        SearchOrderContract $searchOrderContract
    ): Response {
        $merchant = $request->user()->merchants->first();
        [$startDate, $endDate] = $this->getTimePeriodForOrders($request->get('period', 'this-month'));

        $allStatuses = [
            'purchased',
            'processing',
            'acknowledged',
            'fulfilled',
            'rejected',
        ];

        $dashboardMetrics = $repository->getFrontendStatisticsForMerchantWithDateRange(
            $merchant->id,
            $request->get('period', 'this-month')
        );

        $showSignUpDashboardMessage = false;

        if ($request->session()->has('signup_route') && $request->session()->get('signup_route') === true) {
            $showSignUpDashboardMessage = true;
            $request->session()->forget('signup_route');
        }

        if ($request->has('search') && !empty($request->get('search'))) {
            $orders = $searchOrderContract->searchOrdersByStringAndMerchant($request->search, $merchant->id);
        } else {
            $orders = $repository->getOrdersByMerchantAndStatuses(
                $merchant->id,
                $allStatuses,
                'ASC',
                $startDate,
                $endDate
            );
        }

        if ($request->has('status') && !empty($request->get('status'))) {
            $orders = $orders->whereIn(
                'status',
                $repository->getBackendStatusesFromFrontendSearchStatus($request->get('status'))
            );
        }

        if ($request->has('sort')) {
            if ($request->get('sort') === 'desc') {
                $orders = $orders->sortByDesc('created_at');
            }
        }

        return response()->view(
            'admin.dashboard.dashboard',
            [
                'dashboardMetrics' => $dashboardMetrics,
                'bodyClass' => 'body--auth body--dash',
                'orders' => $orders,
                'filterOptions' => $repository->getFormattedStatuses(),
                'statusFilter' => $request->status,
                'merchant' => $merchant,
                'showSignUpDashboardMessage' => $showSignUpDashboardMessage,
                'period' => $request->get('period', 'this-month'),
                'sort' => $request->get('sort', 'asc')
            ]
        );
    }
}
