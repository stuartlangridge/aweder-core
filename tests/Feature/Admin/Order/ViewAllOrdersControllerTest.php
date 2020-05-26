<?php

namespace Tests\Feature\Admin\Order;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Class ViewAllOrdersController
 * @package Tests\Feature\Admin\Order
 * @coversDefaultClass \App\Http\Controllers\Admin\Order\ViewAllOrdersController
 * @group ViewAllOrders
 */
class ViewAllOrdersControllerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * @test
     */
    public function cantAccessPageWhenNotLoggedIn()
    {
        $order = $this->createAndReturnOrderForStatus('Purchased Order');

        $response = $this->get(route('admin.orders.view-all'));

        $response->assertStatus(Response::HTTP_FOUND);

        $response->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function canViewOrdersPageWhenLoggedInAndNoOrders()
    {
        $user = factory(User::class)->create();

        $merchantOne = $this->createAndReturnMerchant(['registration_stage' => 0]);

        $user->merchants()->attach($merchantOne->id);

        $this->actingAs($user);

        $response = $this->get(route('admin.orders.view-all'));

        $response->assertStatus(Response::HTTP_OK);

        $response->assertDontSeeText('Outstanding Orders');

        $response->assertDontSee('outstanding-orders__order col col--lg-12-4 col--l-12-4 col--m-12-6 col--sm-6-6 col--s-6-6 form--background');
    }

    /**
     * @test
     */
    public function canViewOrderPageAndCantSeeOrdersForOtherMerchants()
    {
        $user = factory(User::class)->create();

        $merchantOne = $this->createAndReturnMerchant(['registration_stage' => 0]);

        $merchantTwo = $this->createAndReturnMerchant(['registration_stage' => 0]);

        $order = $this->createAndReturnOrderForStatus('Purchased Order', ['merchant_id' => $merchantTwo->id]);

        $user->merchants()->attach($merchantOne->id);

        $this->actingAs($user);

        $response = $this->get(route('admin.orders.view-all'));

        $response->assertStatus(Response::HTTP_OK);

        $response->assertDontSeeText('Outstanding Orders');

        $response->assertDontSee('outstanding-orders__order col col--lg-12-4 col--l-12-4 col--m-12-6 col--sm-6-6 col--s-6-6 form--background');
    }

    /**
     * @test
     */
    public function canViewOrderPageAndCanSeeOrdersForThem()
    {
        $user = factory(User::class)->create();

        $merchantOne = $this->createAndReturnMerchant(['registration_stage' => 0]);

        $order = $this->createAndReturnOrderForStatus('Purchased Order', ['merchant_id' => $merchantOne->id]);

        $user->merchants()->attach($merchantOne->id);

        $this->actingAs($user);

        $response = $this->get(route('admin.orders.view-all'));

        $response->assertStatus(Response::HTTP_OK);

        $response->assertSeeText('Waiting');

        $response->assertSee('outstanding-orders__order col col--lg-12-5 col--sm-6-6 form--background');
    }

    /**
     *
     */
    public function canSeeOrdersAwaitingFulfillmentForCurrentMerchant()
    {
        $user = factory(User::class)->create();

        $merchantOne = $this->createAndReturnMerchant(['registration_stage' => 0]);

        $order = $this->createAndReturnOrderForStatus('Acknowledged Order', ['merchant_id' => $merchantOne->id]);

        $user->merchants()->attach($merchantOne->id);

        $this->actingAs($user);

        $response = $this->get(route('admin.orders.view-all'));

        $response->assertStatus(Response::HTTP_OK);

        $response->assertSeeText('Not Yet Fulfilled');

        $response->assertSee('outstanding-orders__order col col--lg-12-5 col--sm-6-6 form--background');
    }
}
