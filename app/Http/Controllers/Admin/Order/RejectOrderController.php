<?php

namespace App\Http\Controllers\Admin\Order;

use App\Contract\Service\EmailServiceContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\RejectOrderRequest;
use App\Mail\Merchant\RejectedOrderToCustomer;
use App\Order;
use App\Service\OrderService;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\RedirectResponse;

class RejectOrderController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param RejectOrderRequest $request
     * @param Order $order
     * @param OrderService $orderService
     * @param AuthManager $auth
     * @param EmailServiceContract $emailService
     * @return RedirectResponse
     * @todo Stripe integration needs to be implemented after the order status has been updated to cancel stripe
     */
    public function __invoke(
        RejectOrderRequest $request,
        Order $order,
        OrderService $orderService,
        AuthManager $auth,
        EmailServiceContract $emailService
    ): RedirectResponse {
        if (!$orderService->updateOrderStatus($order, 'rejected')) {
            $request->session()->flash('error', 'There was an error rejecting the order please try again');

            return redirect()->back();
        }

        $merchant = $auth->user()->merchants()->first();

        $orderService->attachMerchantNoteToOrder($order, $request->get('merchant_rejection_reason'));

        $emailService->setToAddress($order->customer_email)
            ->sendEmail(new RejectedOrderToCustomer($order, $merchant));

        //Send out email cancellation to merchant?
        $request->session()->flash(
            'success',
            'The order has been rejected, the customer has been sent a email notifying them'
        );

        return redirect()->route('admin.dashboard');
    }
}
