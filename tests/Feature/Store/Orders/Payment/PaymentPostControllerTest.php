<?php

namespace Tests\Feature\Store\Orders\Payment;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use App\Mail\Customer\OrderPlaced;
use App\Mail\Merchant\OrderPlaced as MerchantOrderPlaced;

/**
 * Class PaymentControllerTest
 * @package Tests\Feature\Store\Orders
 * @coversDefaultClass \App\Http\Controllers\Store\Orders\Payment\PaymentPostController
 * @group Payment
 * @group Order
 */
class PaymentPostControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     */
    public function testPaymentCompletesWithFullDetails()
    {
        $this->markTestSkipped('The test needs re-writing as this is the new card handling route');
        Mail::fake();

        $merchant = $this->createAndReturnMerchant(['allow_delivery' => 1, 'registration_stage' => 0]);

        $order = $this->createAndReturnOrderForStatus('Ready To Buy Order', ['merchant_id' => $merchant->id]);

        $postOrderDetailsRoute = route(
            'store.menu.payment',
            [
                'merchant' => $merchant->url_slug,
                'order' => $order->url_slug
            ]
        );

        $orderDetailsRoute = route(
            'store.menu.order-details',
            [
                'merchant' => $merchant->url_slug,
                'order' => $order->url_slug
            ]
        );

        $thanksPageRoute = route(
            'store.menu.order-thank-you',
            [
                'merchant' => $merchant->url_slug,
                'order' => $order->url_slug
            ]
        );

        Carbon::setTestNow(Carbon::createFromTime(9, 0));

        $postDetails = [
            'customer_name' => $this->faker->name,
            'customer_email' => $this->faker->safeEmail,
            'customer_address' => $this->faker->address,
            'customer_phone' => $this->faker->phoneNumber,
            'collection_type' => 'delivery',
            'order_no' => $order->url_slug,
            'order_time' => [
                'hour' =>  Carbon::createFromTime(9, 0)->addHours(3)->hour,
                'minute' => Carbon::createFromTime(9, 0)->subMinutes(10)->minute,
            ]
        ];

        $response = $this->from($orderDetailsRoute)->post($postOrderDetailsRoute, $postDetails);

        $response->assertRedirect($thanksPageRoute);

        $this->assertDatabaseHas(
            'orders',
            [
                'id' => $order->id,
                'status' => 'purchased'
            ]
        );

        Mail::assertSent(OrderPlaced::class);
        Mail::assertSent(MerchantOrderPlaced::class);
    }
}
