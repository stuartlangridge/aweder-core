<?php

namespace Tests\Unit\Command;

use App\Mail\Merchant\OrderCancelled;
use App\Mail\Merchant\MerchantOrderReminder;
use App\Merchant;
use App\Order;
use App\OrderReminder;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Tests\Traits\InteractsWithEmails;

/**
 * Class SendOrderReminderTest
 * @package Tests\Unit\Command
 * @group OrderReminders
 */
class SendOrderReminderTest extends TestCase
{
    use RefreshDatabase;
    use InteractsWithEmails;

    /** @test */
    public function willOnlySendReminderEmailToOrderInSpecifiedTimeSince(): void
    {
        Mail::fake();

        $merchant = factory(Merchant::class)->create();

        $order = factory(Order::class)->state('Unprocessed Order')->create([
            'created_at' => Carbon::parse()->subMinutes(20),
            'order_submitted' => Carbon::parse()->subMinutes(20),
            'merchant_id' => $merchant->id,
        ]);

        $merchantWithOrderOutsideReminderTimeframe = factory(Merchant::class)->create();

        $unprocessedOrder = factory(Order::class)->state('Unprocessed Order')->create([
            'created_at' => Carbon::parse()->subMinutes(31),
            'order_submitted' => Carbon::parse()->subMinutes(31),
            'merchant_id' => $merchantWithOrderOutsideReminderTimeframe->id,
        ]);

        $unprocessedOrderTwo = factory(Order::class)->state('Unprocessed Order')->create([
            'created_at' => Carbon::parse()->subMinutes(19),
            'order_submitted' => Carbon::parse()->subMinutes(19),
            'merchant_id' => $merchantWithOrderOutsideReminderTimeframe->id,
        ]);

        $this->artisan('orders:send-reminders', ['minutes' => 20]);

        $this->assertMailWasSentToEmailAddress(
            MerchantOrderReminder::class,
            $merchant->contact_email
        );

        $this->assertMailWasNotSentToEmailAddress(
            MerchantOrderReminder::class,
            $merchantWithOrderOutsideReminderTimeframe->contact_email
        );
    }

    /** @test */
    public function willOnlySendReminderToOrderThatDoesntHaveAPreviousEmailReminderForTimeSent(): void
    {
        Mail::fake();

        $minutes = 20;

        $merchant = factory(Merchant::class)->create();

        $order = factory(Order::class)->state('Unprocessed Order')->create([
            'created_at' => Carbon::parse()->subMinutes(20),
            'order_submitted' => Carbon::parse()->subMinutes(20),
            'merchant_id' => $merchant->id,
        ]);

        $reminderSent = new OrderReminder([
            'reminder_time' => $minutes,
            'sent' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        $order->reminders()->save($reminderSent);

        $merchantTwoInsideTimeFrame = factory(Merchant::class)->create();

        $orderTwo = factory(Order::class)->state('Unprocessed Order')->create([
            'created_at' => Carbon::parse()->subMinutes(20),
            'order_submitted' => Carbon::parse()->subMinutes(20),
            'merchant_id' => $merchantTwoInsideTimeFrame->id,
        ]);

        $merchantWithOrderOutsideReminderTimeframe = factory(Merchant::class)->create();

        $unprocessedOrder = factory(Order::class)->state('Unprocessed Order')->create([
            'created_at' => Carbon::parse()->subMinutes(35),
            'order_submitted' => Carbon::parse()->subMinutes(35),
            'merchant_id' => $merchantWithOrderOutsideReminderTimeframe->id,
        ]);

        $unprocessedOrderTwo = factory(Order::class)->state('Unprocessed Order')->create([
            'created_at' => Carbon::parse()->subMinutes(19),
            'order_submitted' => Carbon::parse()->subMinutes(19),
            'merchant_id' => $merchantWithOrderOutsideReminderTimeframe->id,
        ]);

        $this->artisan('orders:send-reminders', ['minutes' => $minutes]);

        $this->assertMailWasNotSentToEmailAddress(
            MerchantOrderReminder::class,
            $merchant->contact_email
        );

        $this->assertMailWasSentToEmailAddress(
            MerchantOrderReminder::class,
            $merchantTwoInsideTimeFrame->contact_email
        );

        $this->assertMailWasNotSentToEmailAddress(
            MerchantOrderReminder::class,
            $merchantWithOrderOutsideReminderTimeframe->contact_email
        );
    }
}
