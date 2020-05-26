<?php

namespace Tests\Feature\Store\Payments;

use App\Contract\Service\StripeContract;
use App\Merchant;
use App\Order;
use App\Provider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

/**
 * Class CreateControllerTest
 * @package Tests\Feature\Store\Payments
 * @coversDefaultClass \App\Http\Controllers\Store\Payments\CreateController
 * @group Payment
 * @group Stripe
 */
class CreateControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var StripeContract $service
     */
    protected StripeContract $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app()->make(StripeContract::class);
    }

    /**
     * @test
     */
    public function createPaymentIntentFlowWorksAsExpected(): void
    {
        $this->markTestSkipped('These need to be re-written to handle the new flow');
        $service = Mockery::mock($this->service)->makePartial();

        app()->instance(StripeContract::class, $service);

        $service->shouldReceive('createPaymentIntent')->once()->andReturn([
            'success' => true,
            'intent' => (object) ['id' => 'test', 'status' => 'requires_confirmation', 'client_secret' => 'secret']

        ]);

        $merchant = factory(Merchant::class)->create();

        $provider = factory(Provider::class)->create();

        $stripeData = [
            'publicKey' => 'pk_test_wbMOeKaDtEWrKOuoHB4wkkxv',
            'secretKey' => 'sk_test_gIdbURO9NANABhhugjl4Ginj',
        ];

        $merchant->paymentProviders()->attach($provider->id, ['data' => json_encode($stripeData) ]);

        $order = factory(Order::class)->create([
            'merchant_id' => $merchant->id
        ]);

        $route = route(
            'store.payment.create',
            [
                'merchant' => $merchant->url_slug,
                'order' => $order->url_slug
            ]
        );
        $this->post($route, ['payment_method_id' => 'test', 'amount' => 400])
            ->assertOk()
            ->assertJson([
            'result' => true,
            'intent' => ['id' => 'test', 'action' => 'requires_confirmation', 'secret' => 'secret']
            ]);
    }
    /**
     * @test
     */
    public function createPaymentIntentFlowWithFailedIntent(): void
    {
        $this->markTestSkipped('The test needs re-writing as this is the new card handling route');

        $service = Mockery::mock($this->service)->makePartial();

        app()->instance(StripeContract::class, $service);

        $service->shouldReceive('createPaymentIntent')->once()->andReturn([
            'success' => false,
        ]);

        $merchant = factory(Merchant::class)->create();

        $provider = factory(Provider::class)->create();

        $stripeData = [
            'publicKey' => '',
            'secretKey' => '',
        ];

        $merchant->paymentProviders()->attach($provider->id, ['data' => json_encode($stripeData) ]);


        $order = factory(Order::class)->create([
            'merchant_id' => $merchant->id
        ]);

        $route = route(
            'store.payment.create',
            [
                'merchant' => $merchant->url_slug,
                'order' => $order->url_slug
            ]
        );
        $this->post($route, ['payment_method_id' => 'test', 'amount' => 400])
            ->assertOk()
            ->assertJson([
                'result' => false,
                'error' => 'There was an error creating payment'
            ]);
    }
}
