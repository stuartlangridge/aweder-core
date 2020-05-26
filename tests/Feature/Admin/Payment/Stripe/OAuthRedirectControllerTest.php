<?php

namespace Tests\Feature\Admin\Payment\Stripe;

use App\Contract\Service\StripeContract;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Stripe\StripeObject;
use Tests\TestCase;

/**
 * Class OAuthRedirectControllerTest
 * @package Tests\Feature\Admin\Payment\Stripe
 * @coversDefaultClass \App\Http\Controllers\Admin\Payment\Stripe\OAuthRedirectController
 * @group Payments
 */
class OAuthRedirectControllerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * @test
     */
    public function useCantReachRouteWithoutBeingLoggedIn()
    {
        $response = $this->get(route('admin.stripe-oauth.redirect'));

        $response->assertStatus(Response::HTTP_FOUND);

        $response->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function missMatchingStateReturnsInvalidRequest()
    {
        $user = factory(User::class)->create();

        $merchantOne = $this->createAndReturnMerchant(['registration_stage' => 0]);

        $user->merchants()->attach($merchantOne->id);

        $this->actingAs($user);

        $state = '123123123';

        $response = $this->get(route('admin.stripe-oauth.redirect', ['state' => $state]));

        $response->assertRedirect(route('admin.details.edit'));

        $response->assertSessionHas('error', 'Incorrect state parameter: ' . $state);
    }

    /**
     * @test
     * @group CorrectState
     *
     */
    public function correctStateButNoCodePresent()
    {
        $user = factory(User::class)->create();

        $merchantOne = $this->createAndReturnMerchant(
            [
                'registration_stage' => 0,
                'id' => 1,
                'url_slug' => 'tester',
                'salt' => 12345
            ]
        );

        $user->merchants()->attach($merchantOne->id);

        $this->actingAs($user);

        $stripeService = app()->make(StripeContract::class);

        $state = $stripeService->createUserStateForStripe($merchantOne->id, $merchantOne->url_slug, $merchantOne->salt);

        $code = 'tester';

        $response = $this->get(route('admin.stripe-oauth.redirect', ['state' => $state, 'code' => $code]));

        $response->assertRedirect(route('admin.details.edit'));

        $response->assertSessionHas('error', 'Invalid authorization code: ' . $code);
    }

    /**
     * @test
     */
    public function testRedirectHasErrorPresentAndHandlesAsExpected()
    {
        $user = factory(User::class)->create();

        $merchantOne = $this->createAndReturnMerchant(
            [
                'registration_stage' => 0,
                'id' => 1,
                'url_slug' => 'tester',
                'salt' => 12345
            ]
        );

        $user->merchants()->attach($merchantOne->id);

        $this->actingAs($user);

        $response = $this->get(route('admin.stripe-oauth.redirect', ['error' => 'herere']));

        $response->assertRedirect(route('admin.details.edit'));

        $response->assertSessionHas('error', 'There was an error authenticating please try again');
    }

    /**
     *
     * @test
     */
    public function correctStateAndCodePresentCheckingReturnedCorrectly()
    {
        $user = factory(User::class)->create();

        $merchantOne = $this->createAndReturnMerchant(
            [
                'registration_stage' => 0,
                'id' => 1,
                'url_slug' => 'tester',
                'salt' => 12345
            ]
        );

        $this->createAndReturnPaymentProvider();

        $user->merchants()->attach($merchantOne->id);

        $this->actingAs($user);

        $stripeService = app()->make(StripeContract::class);

        $state = $stripeService->createUserStateForStripe($merchantOne->id, $merchantOne->url_slug, $merchantOne->salt);

        $code = 'tester';

        $this->partialMock(StripeContract::class, function ($mock) {
            $mock->shouldReceive('verifyUserStateForStripe')->once()->andReturn(true)
                ->shouldReceive('getOAuthTokenFromStripeWithCode')->once()->andReturn(new StripeObject());
        });

        $this->createAndReturnPaymentProvider(['id' => 1]);

        $response = $this->get(route('admin.stripe-oauth.redirect', ['state' => $state, 'code' => $code]));

        $response->assertRedirect(route('admin.details.edit'));

        $response->assertSessionHas('success', 'Stripe has been added to your account');
    }
}
