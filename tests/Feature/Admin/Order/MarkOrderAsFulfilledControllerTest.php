<?php

namespace Tests\Feature\Admin\Order;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Class MarkOrderAsFulfilledControllerTest
 * @package Tests\Feature\Admin\Order
 * @coversDefaultClass \App\Http\Controllers\Admin\Order\MarkOrderAsFulfilledController
 * @group MarkAsFilled
 */
class MarkOrderAsFulfilledControllerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;


    /**
     * checks that a unauthenticated user cant view the route
     * @test
     */
    public function unauthenticatedUserCantRejectAnOrder()
    {

        $order = $this->createAndReturnOrderForStatus('Purchased Order');

        $response = $this->get(route('admin.order-fulfilled', $order->url_slug));

        $response->assertStatus(Response::HTTP_FOUND);

        $response->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function merchantWhoHasntFinishedRegistrationCantAcceptOrder()
    {
        $user = factory(User::class)->create();

        $merchantOne = $this->createAndReturnMerchant(['registration_stage' => 3]);

        $user->merchants()->attach($merchantOne->id);

        $order = $this->createAndReturnOrderForStatus('Purchased Order', ['merchant_id' => $merchantOne->id]);

        $this->actingAs($user);

        $response = $this->get(route('admin.order-fulfilled', $order->url_slug));

        $response->assertStatus(Response::HTTP_FOUND);

        $response->assertRedirect(route('register.contact-details'));
    }

    /**
     * @test
     */
    public function merchantCantMarkOrderAsFulfilledIfTheyDontOwnIt()
    {
        $user = factory(User::class)->create();

        $merchantOne = $this->createAndReturnMerchant(['registration_stage' => 0]);

        $user->merchants()->attach($merchantOne->id);

        $merchantTwo = $this->createAndReturnMerchant();

        $order = $this->createAndReturnOrderForStatus('Purchased Order', ['merchant_id' => $merchantTwo->id]);

        $this->actingAs($user);

        $this->assertDatabaseHas(
            'orders',
            [
                'id' => $order->id,
                'status' => 'purchased',
            ]
        );

        $response = $this->get(route('admin.order-fulfilled', $order->url_slug));

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
    public function merchantCanMarkHisOwnOrderAsFulffiled()
    {
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

        $this->assertDatabaseHas(
            'orders',
            [
                'id' => $order->id,
                'status' => 'acknowledged',
            ]
        );

        $response = $this->from(route('admin.dashboard'))
            ->get(route('admin.order-fulfilled', $order->url_slug));

        $response->assertStatus(Response::HTTP_FOUND);

        $response->assertRedirect(route('admin.dashboard'));

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas(
            'orders',
            [
                'id' => $order->id,
                'status' => 'fulfilled',
            ]
        );
    }

    /**
     * @test
     * this makes sure the status cant be changed
     */
    public function merchantCantFulfillAnOrderThatsBeenRejected()
    {
        $user = factory(User::class)->create();

        $merchantOne = $this->createAndReturnMerchant(['registration_stage' => 0]);

        $user->merchants()->attach($merchantOne->id);

        $order = $this->createAndReturnOrderForStatus(
            'Rejected Order',
            [
                'merchant_id' => $merchantOne->id
            ]
        );

        $this->actingAs($user);

        $this->assertDatabaseHas(
            'orders',
            [
                'id' => $order->id,
                'status' => 'rejected',
            ]
        );

        $response = $this->from(route('admin.dashboard'))
            ->get(route('admin.order-fulfilled', $order->url_slug));

        $response->assertStatus(Response::HTTP_FOUND);

        $response->assertRedirect(route('admin.dashboard'));

        $this->assertDatabaseHas(
            'orders',
            [
                'id' => $order->id,
                'status' => 'rejected',
            ]
        );
    }

    /**
     * @test
     */
    public function merchantCantMarkAItemAsFulfilledWithoutItBeingAcceptedFirst()
    {
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

        $this->assertDatabaseHas(
            'orders',
            [
                'id' => $order->id,
                'status' => 'purchased',
            ]
        );

        $response = $this->from(route('admin.dashboard'))
            ->get(route('admin.order-fulfilled', $order->url_slug));

        $response->assertStatus(Response::HTTP_FOUND);

        $response->assertRedirect(route('admin.dashboard'));

        $response->assertSessionHas('error', 'The order needs to be accepted first');

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
     */
    public function cantMarkAOrderASFulfilledWithoutBeingPurchased()
    {
        $user = factory(User::class)->create();

        $merchantOne = $this->createAndReturnMerchant(['registration_stage' => 0]);

        $user->merchants()->attach($merchantOne->id);

        $order = $this->createAndReturnOrderForStatus(
            'Incomplete Order',
            [
                'merchant_id' => $merchantOne->id
            ]
        );

        $this->actingAs($user);

        $this->assertDatabaseHas(
            'orders',
            [
                'id' => $order->id,
                'status' => 'incomplete',
            ]
        );

        $response = $this->from(route('admin.dashboard'))
            ->get(route('admin.order-fulfilled', $order->url_slug));

        $response->assertStatus(Response::HTTP_FOUND);

        $response->assertRedirect(route('admin.dashboard'));

        $response->assertSessionHas('error', 'The order needs to be accepted first');

        $this->assertDatabaseHas(
            'orders',
            [
                'id' => $order->id,
                'status' => 'incomplete',
            ]
        );
    }
}
