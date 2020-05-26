<?php

namespace App\Console\Commands;

use App\Contract\Repositories\OrderContract;
use App\Contract\Service\EmailServiceContract;
use App\Mail\Merchant\OrderCancelled as MerchantOrderCancelled;
use App\Mail\Customer\OrderCancelled;
use App\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FetchCancellableOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cancellable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch orders not processed in the 30 minutes since their creation and cancel them.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param OrderContract $orders
     * @param EmailServiceContract $emailService
     *
     * @return void
     */
    public function handle(OrderContract $orders, EmailServiceContract $emailService): void
    {
        $nonProcessedOrders = $orders->getUnprocessedOrdersBetweenPeriod(
            Carbon::parse()->subMinutes(60),
            Carbon::parse()->subMinutes(30)
        );

        $nonProcessedOrders->each(function (Order $order) use (
            $emailService,
            $orders
        ) {
            $customerMailable = new OrderCancelled($order->merchant, $order);

            $merchantMailable = new MerchantOrderCancelled($order->merchant, $order);

            $emailService->setToAddress($order->customer_email)->sendEmail($customerMailable);
            $emailService->setToAddress($order->merchant->contact_email)->sendEmail($merchantMailable);
            $orders->setOrderToUnacknowledged($order);
        });
    }
}
