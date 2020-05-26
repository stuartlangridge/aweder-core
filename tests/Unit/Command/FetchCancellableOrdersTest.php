<?php

namespace Tests\Unit\Command;

use App\Mail\Merchant\OrderCancelled;
use App\Mail\Customer\OrderCancelled as CustomerCancelled;
use App\Merchant;
use App\Order;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Tests\Traits\InteractsWithEmails;

/**
 * Class FetchCancellableOrdersTest
 * @package Tests\Unit\Command
 * @coversDefaultClass \App\Console\Commands\FetchCancellableOrders
 * @group Commands
 */
class FetchCancellableOrdersTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use InteractsWithEmails;

    /**  */
    public function willSetOrderToCancelledWhenOrderIsCancellable(): void
    {
        Mail::fake();

        $unprocessedOrder = factory(Order::class)->state('Unprocessed Order')->create();
        $incompleteOrder = factory(Order::class)->state('Incomplete Order')->create();
        $purchasedOrder = factory(Order::class)->state('Purchased Order')->create();

        $this->artisan('orders:cancellable');

        $this->assertEquals($unprocessedOrder->fresh()->status, 'unacknowledged');
        $this->assertEquals($incompleteOrder->fresh()->status, 'incomplete');
        $this->assertEquals($purchasedOrder->fresh()->status, 'purchased');
    }

    /** @test */
    public function willOnlySendEmailToUsersWithUnprocessedOrder(): void
    {
        Mail::fake();

        $unprocessedOrder = factory(Order::class)->state('Unprocessed Order')->create();

        $incompleteOrder = factory(Order::class)->state('Incomplete Order')->create();

        $purchasedOrder = factory(Order::class)->state('Purchased Order')->create();

        $this->artisan('orders:cancellable');

        $this->assertMailableWasSent(
            CustomerCancelled::class,
            __('mail.order.cancelled.customer.subject'),
            $unprocessedOrder->customer_email
        );

        $this->assertMailableWasNotSent(
            CustomerCancelled::class,
            __('mail.order.cancelled.customer.subject'),
            $incompleteOrder->customer_email
        );

        $this->assertMailableWasNotSent(
            CustomerCancelled::class,
            __('mail.order.cancelled.customer.subject'),
            $purchasedOrder->customer_email
        );
    }

    /** @test */
    public function willOnlySendEmailToMerchantsWithUnprocessedOrder(): void
    {
        Mail::fake();

        $merchantWithCancelledOrder = factory(Merchant::class)->create();

        $merchantWithNoCancelledOrders = factory(Merchant::class)->create();

        $orderOne = factory(Order::class)->state('Unprocessed Order')
            ->create(['merchant_id' => $merchantWithCancelledOrder->id]);

        factory(Order::class)->state('Incomplete Order')
            ->create(['merchant_id' => $merchantWithNoCancelledOrders->id]);

        factory(Order::class)->state('Purchased Order')->create([
            'merchant_id' => $merchantWithNoCancelledOrders->id
        ]);

        $this->artisan('orders:cancellable');

        $this->assertMailableWasSent(
            OrderCancelled::class,
            str_replace('<orderid>', $orderOne->url_slug, __('mail.order.cancelled.merchant.subject')),
            $merchantWithCancelledOrder->contact_email
        );

        $this->assertMailableWasNotSent(
            OrderCancelled::class,
            __('mail.order.cancelled.merchant.subject'),
            $merchantWithNoCancelledOrders->contact_email
        );
    }
}
