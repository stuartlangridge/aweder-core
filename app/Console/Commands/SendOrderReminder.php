<?php

namespace App\Console\Commands;

use App\Contract\Repositories\OrderContract;
use App\Contract\Service\EmailServiceContract;
use App\Mail\Merchant\MerchantOrderReminder;
use App\Order;
use App\OrderReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendOrderReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:send-reminders {minutes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch unprocessed orders and send reminder to merchant.';

    /**
     * Execute the console command.
     *
     * @param OrderContract $orders
     * @param EmailServiceContract $emailService
     *
     * @return mixed
     */
    public function handle(OrderContract $orders, EmailServiceContract $emailService)
    {
        $minutes = (int) $this->argument('minutes');

        $minutesAdded = (int) $minutes + 5;

        $nonProcessedOrders = $orders->getUnprocessedOrdersBetweenPeriodWhereNoRemindersHaveBeenSentForTime(
            Carbon::now()->subMinutes($minutesAdded)->addSecond(),
            Carbon::now()->subMinutes($minutes),
            $minutes
        );

        $nonProcessedOrders->each(function (Order $order) use ($emailService, $minutes) {
            $reminderMailable = new MerchantOrderReminder($order->merchant, $order);

            $emailService->setToAddress($order->merchant->contact_email)->sendEmail($reminderMailable);
            $reminderSent = new OrderReminder([
                'reminder_time' => $minutes,
                'sent' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            $order->reminders()->save($reminderSent);
        });
    }
}
