<?php

namespace Tests\Feature\Admin\Order;

use App\Mail\Merchant\RejectedOrderToCustomer;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Class RejectOrderControllerTest
 * @package Tests\Feature\Admin\Order
 * @coversDefaultClass \App\Http\Controllers\Admin\Order\RejectOrderController
 * @group Order
 */
class RejectOrderControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;


    /**
     * checks that a unauthenticated user cant view the route
     * @test
     */
    public function unauthenticatedUserCantRejectAnOrder()
    {
        $order = $this->createAndReturnOrderForStatus('Purchased Order');

        $response = $this->post(route('admin.reject-order', $order->url_slug));

        $response->assertStatus(Response::HTTP_FOUND);

        $response->assertRedirect(route('login'));
    }

    /**
     * @test
     *
     */
    public function merchantCantRejectAnOrderTheyDontOwn()
    {
        $user = factory(User::class)->create();

        $merchantOne = $this->createAndReturnMerchant();

        $user->merchants()->attach($merchantOne->id);

        $merchantTwo = $this->createAndReturnMerchant(['registration_stage' => 0]);

        $order = $this->createAndReturnOrderForStatus('Purchased Order', ['merchant_id' => $merchantTwo->id]);

        $this->actingAs($user);

        $this->assertDatabaseHas(
            'orders',
            [
                'id' => $order->id,
                'status' => 'purchased',
            ]
        );

        $rejectionReason = $this->faker->sentence;

        $response = $this->post(
            route('admin.reject-order', $order->url_slug),
            ['merchant_rejection_reason' => $rejectionReason]
        );

        $response->assertStatus(Response::HTTP_FOUND);

        $response->assertRedirect(route('admin.dashboard'));

        $this->assertDatabaseHas(
            'orders',
            [
                'id' => $order->id,
                'status' => 'purchased',
            ]
        );
    }

    /**
     * @test
     * @group OwnOrder
     * This test covers the rejection of an order.
     */
    public function merchantCanRejectHisOwnOrder()
    {
        Mail::fake();

        // Assert that no mailables were sent...
        Mail::assertNothingSent();

        $user = factory(User::class)->create();

        $merchantOne = $this->createAndReturnMerchant(['registration_stage' => 0]);

        $user->merchants()->attach($merchantOne->id);

        $order = $this->createAndReturnOrderForStatus(
            'Purchased Order',
            [
                'merchant_id' => $merchantOne->id
            ]
        );

        $this->actingAs($user);

        $rejectionReason = $this->faker->sentence;

        $this->assertDatabaseHas(
            'orders',
            [
                'id' => $order->id,
                'status' => 'purchased',
            ]
        );

        $response = $this->post(
            route('admin.reject-order', $order->url_slug),
            ['merchant_rejection_reason' => $rejectionReason]
        );

        $response->assertStatus(Response::HTTP_FOUND);

        $response->assertRedirect(route('admin.dashboard'));

        $this->assertDatabaseHas(
            'orders',
            [
                'id' => $order->id,
                'status' => 'rejected',
                'merchant_note' => $rejectionReason,
            ]
        );

        Mail::assertSent(RejectedOrderToCustomer::class);
    }

    /**
     * @test
     * this makes sure the status cant be changed
     */
    public function merchantCantRejectAnOrderThatsBeenAcknowledged()
    {
        Mail::fake();

        // Assert that no mailables were sent...
        Mail::assertNothingSent();

        $user = factory(User::class)->create();

        $merchantOne = $this->createAndReturnMerchant(['registration_stage' => 0]);

        $user->merchants()->attach($merchantOne->id);

        $order = $this->createAndReturnOrderForStatus(
            'Acknowledged Order',
            [
                'merchant_id' => $merchantOne->id
            ]
        );

        $this->actingAs($user);

        $rejectionReason = $this->faker->sentence;

        $this->assertDatabaseHas(
            'orders',
            [
                'id' => $order->id,
                'status' => 'acknowledged',
            ]
        );

        $response = $this->from(route('admin.view-order', $order->url_slug))->post(
            route('admin.reject-order', $order->url_slug),
            ['merchant_rejection_reason' => $rejectionReason]
        );

        $response->assertStatus(Response::HTTP_FOUND);

        $response->assertRedirect(route('admin.view-order', $order->url_slug));

        $this->assertDatabaseHas(
            'orders',
            [
                'id' => $order->id,
                'status' => 'acknowledged',
            ]
        );

        Mail::assertNothingSent();
    }
}
