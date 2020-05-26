<?php

namespace App\Http\Controllers\Admin\Order;

use App\Contract\Repositories\OrderContract;
use App\Contract\Service\EmailServiceContract;
use App\Contract\Service\OrderContract as OrderService;
use App\Http\Controllers\Controller;
use App\Http\Requests\AcceptOrderRequest;
use App\Mail\Customer\OrderAccepted as CustomerOrderAccepted;
use App\Mail\Merchant\OrderAccepted;
use App\Order;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\RedirectResponse;

class AcceptOrderController extends Controller
{
    /**
     * Handle the incoming request.
     * @param AcceptOrderRequest $request
     * @param OrderContract $repository
     * @param OrderService $service
     * @param EmailServiceContract $emailService
     * @param AuthManager $auth
     * @param $order
     *
     * @return RedirectResponse
     *
     * @todo Stripe integration needs to be implemented after the order status has been updated
     */
    public function __invoke(
        AcceptOrderRequest $request,
        OrderContract $repository,
        OrderService $service,
        EmailServiceContract $emailService,
        AuthManager $auth,
        Order $order
    ): RedirectResponse {
        if (!$service->updateOrderStatus($order, 'acknowledged')) {
            $request->session()->flash('error', 'There was an error accepting the order please try again');

            return redirect()->back();
        }

        $inputAvailableTime =  $request->input('available_time')->toDateTimeString();

        if (!$repository->updateAvailableTimeOnOrder($order, $inputAvailableTime)) {
            $request->session()->flash('error', 'There was an error accepting the order please try again');

            return redirect()->back();
        }

        if ($request->has('merchant_note') && !empty($request->get('merchant_note'))) {
            if (!$service->attachMerchantNoteToOrder($order, $request->input('merchant_note'))) {
                $request->session()->flash('error', 'There was an error accepting the order please try again');

                return redirect()->back();
            }
        }

        $merchant = $auth->user()->merchants->first();

        $emailService->setToAddress($order->customer_email)
            ->sendEmail(new CustomerOrderAccepted($order, $merchant));

        $emailService->setToAddress($order->merchant->contact_email)
            ->sendEmail(new OrderAccepted($order, $merchant));

        $request->session()->flash(
            'success',
            'The order has been accepted, the customer has been sent a email notifying them'
        );

        return redirect()->route('admin.dashboard');
    }
}
