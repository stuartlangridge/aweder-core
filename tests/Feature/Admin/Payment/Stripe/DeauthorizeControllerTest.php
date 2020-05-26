<?php

namespace Tests\Feature\Admin\Payment\Stripe;

use App\Contract\Service\StripeContract;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Mockery\Exception;
use Mockery\Mock;
use Tests\TestCase;

/**
 * Class DeauthorizeControllerTest
 * @package Tests\Feature\Admin\Payment\Stripe
 * @coversDefaultClass \App\Http\Controllers\Admin\Payment\Stripe\DeauthorizeController
 * @group Payments
 */
class DeauthorizeControllerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * @test
     */
    public function useCantReachRouteWithoutBeingLoggedIn()
    {
        $response = $this->get(route('admin.stripe-oauth.deauthorize'));

        $response->assertStatus(Response::HTTP_FOUND);

        $response->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function noPaymentIntegrationOnReturnsError()
    {
        $user = factory(User::class)->create();

        $merchantOne = $this->createAndReturnMerchant(['registration_stage' => 0]);

        $user->merchants()->attach($merchantOne->id);

        $this->actingAs($user);

        $state = '123123123';

        $response = $this->get(route('admin.stripe-oauth.deauthorize'));

        $response->assertRedirect(route('admin.details.edit'));

        $response->assertSessionHas('error', 'You don\'t have stripe authorised on your account');
    }

    /**
     * @test
     * @group Deauth
     */
    public function handlesDeauthorizationFailure()
    {
        $user = factory(User::class)->create();

        $merchantOne = $this->createAndReturnMerchant(['registration_stage' => 0]);

        $user->merchants()->attach($merchantOne->id);

        $this->actingAs($user);

        $state = '123123123';

        $this->createAndReturnPaymentProvider(['id' => 1]);

        $merchantOne->paymentProviders()->attach(1, ['data' => json_encode(['asdasd' => 'asdsad'])]);

        $response = $this->get(route('admin.stripe-oauth.deauthorize'));

        $response->assertRedirect(route('admin.details.edit'));

        $response->assertSessionHas('error', 'There was an error with the action please try again');
    }
}
