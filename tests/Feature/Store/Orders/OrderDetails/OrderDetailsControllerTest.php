<?php

namespace Tests\Feature\Store\Orders\OrderDetails;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Tests\TestCase;

/**
 * Class OrderDetailsControllerTest
 * @package Tests\Feature\Store\Orders
 * @coversDefaultClass \App\Http\Controllers\Store\Orders\OrderDetails\OrderDetailsController
 * @group Orders
 */
class OrderDetailsControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * once a customer has moved the order past the purchased stage there should be now way to change order details on
     * that page.
     * @test
     */
    public function cannotViewOrderDetailsPageOnceOrderHasGonePastACustomerSubmissionStage()
    {
        Mail::fake();
        $merchant = $this->createAndReturnMerchant();

        $order = $this->createAndReturnOrderForStatus('Acknowledged Order', ['merchant_id' => $merchant->id]);

        $storeRoute = route('store.menu.view', ['merchant' => $merchant->url_slug, 'order' => $order->url_slug]);

        $storeRouteNoOrder = route(
            'store.menu.view',
            [
                'merchant' => $merchant->url_slug,
            ]
        );

        $orderDetailsRoute = route(
            'store.menu.order-details',
            [
                'merchant' => $merchant->url_slug,
                'order' => $order->url_slug
            ]
        );

        $response = $this->from($storeRoute)->get($orderDetailsRoute);

        $response->assertRedirect($storeRouteNoOrder);

        $this->assertDatabaseHas(
            'orders',
            [
                'id' => $order->id,
                'status' => 'acknowledged'
            ]
        );
    }

    /**
     * this stops a user from viewig a order details page for a different merchant to stop guess attacks
     * @test
     */
    public function cannotViewOrderDetailsPageOfOrderThatIsWithADifferentMerchant()
    {
        Mail::fake();
        $merchant = $this->createAndReturnMerchant();

        $merchantTwo = $this->createAndReturnMerchant();

        $order = $this->createAndReturnOrderForStatus('Incomplete Order', ['merchant_id' => $merchant->id]);

        $storeRoute = route('store.menu.view', ['merchant' => $merchant->url_slug, 'order' => $order->url_slug]);

        $storeRouteNoOrder = route(
            'store.menu.view',
            [
                'merchant' => $merchant->url_slug,
            ]
        );

        $homeRoute = route('home');

        $orderDetailsRoute = route(
            'store.menu.order-details',
            [
                'merchant' => $merchantTwo->url_slug,
                'order' => $order->url_slug
            ]
        );

        $response = $this->from($storeRoute)->get($orderDetailsRoute);

        $response->assertRedirect($homeRoute);

        $this->assertDatabaseHas(
            'orders',
            [
                'id' => $order->id,
                'status' => 'incomplete'
            ]
        );
    }

    /**
     * @test
     */
    public function canViewOrderDetailsForOrderThatBelongsToMerchantInTheDomain()
    {
        Mail::fake();
        $merchant = $this->createAndReturnMerchant();

        $merchantTwo = $this->createAndReturnMerchant();

        $order = $this->createAndReturnOrderForStatus('Incomplete Order', ['merchant_id' => $merchant->id]);

        $storeRoute = route('store.menu.view', ['merchant' => $merchant->url_slug, 'order' => $order->url_slug]);

        $homeRoute = route('home');

        $orderDetailsRoute = route(
            'store.menu.order-details',
            [
                'merchant' => $merchant->url_slug,
                'order' => $order->url_slug
            ]
        );

        $response = $this->from($storeRoute)->get($orderDetailsRoute);

        $this->assertDatabaseHas(
            'orders',
            [
                'id' => $order->id,
                'status' => 'ready-to-buy'
            ]
        );
    }

    /**
     * @test
     * @group ValidationOrder
     */
    public function validationCorrectWhenOrderDetailsAreNotFilledIn()
    {
        Mail::fake();

        $merchant = $this->createAndReturnMerchant();

        $order = $this->createAndReturnOrderForStatus('Incomplete Order', ['merchant_id' => $merchant->id]);

        $postOrderDetailsRoute = route(
            'store.menu.order-details',
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

        $response = $this->from($orderDetailsRoute)->post($postOrderDetailsRoute, []);

        $response->assertRedirect($orderDetailsRoute);

        $this->assertDatabaseHas(
            'orders',
            [
                'id' => $order->id,
                'status' => 'incomplete'
            ]
        );
    }

    /**
     * @test
     */
    public function settingCustomerRequestedTimeForOrderThatIsPastOrderShouldReject()
    {
        $this->markTestSkipped('This test isnt required as we dont validate time anymore but ive kept in just incase we bring back');
        Mail::fake();

        $merchant = $this->createAndReturnMerchant(['allow_delivery' => 1]);

        $order = $this->createAndReturnOrderForStatus('Incomplete Order', ['merchant_id' => $merchant->id]);

        $postOrderDetailsRoute = route(
            'store.menu.order-details',
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

        $postDetails = [
            'customer_name' => $this->faker->name,
            'customer_email' => $this->faker->safeEmail,
            'customer_address' => $this->faker->address,
            'customer_phone' => $this->faker->phoneNumber,
            'collection_type' => 'delivery',
            'order_no' => $order->url_slug,
            'order_time' => [
                'hour' =>  Carbon::now()->subHour()->hour,
                'minute' => Carbon::now()->subMinutes(10)->minute,
            ]
        ];

        $response = $this->from($orderDetailsRoute)->post($postOrderDetailsRoute, $postDetails);

        $response->assertRedirect($orderDetailsRoute);

        $response->assertSessionHasErrors('order_time');

        $this->assertDatabaseHas(
            'orders',
            [
                'id' => $order->id,
                'status' => 'incomplete'
            ]
        );
    }

    /**
     * @test
     * @group OrderDelivery
     */
    public function settingIncorrectDeliveryTypeForMerchantToMakeSureAResponseIsReturned()
    {
        $this->markTestSkipped('The flow no longer works like this, but handy for reference at the moment');

        Mail::fake();

        $merchant = $this->createAndReturnMerchant(['allow_delivery' => 0]);

        $order = $this->createAndReturnOrderForStatus('Incomplete Order', ['merchant_id' => $merchant->id]);

        $postOrderDetailsRoute = route(
            'store.menu.order-details',
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

        $postDetails = [
            'customer_name' => $this->faker->name,
            'customer_email' => $this->faker->safeEmail,
            'customer_address' => $this->faker->address,
            'customer_phone' => $this->faker->phoneNumber,
            'collection_type' => 'delivery',
            'order_no' => $order->url_slug,
            'order_time' => [
                'hour' =>  Carbon::now()->subHour()->hour,
                'minute' => Carbon::now()->subMinutes(10)->minute,
            ]
        ];

        $response = $this->from($orderDetailsRoute)->post($postOrderDetailsRoute, $postDetails);

        $response->assertRedirect($orderDetailsRoute);

        $response->assertSessionHasErrors('collection_type');

        $this->assertDatabaseHas(
            'orders',
            [
                'id' => $order->id,
                'status' => 'incomplete'
            ]
        );
    }
}
