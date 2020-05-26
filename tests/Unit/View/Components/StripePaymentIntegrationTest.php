<?php

namespace Tests\Unit\View\Components;

use App\Contract\Service\StripeContract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\View\Components\StripePaymentIntegration;
use Tests\TestCase;

/**
 * Class StripePaymentIntegration
 * @package Tests\Unit\View\Components
 * @coversDefaultClass \App\View\Components\StripePaymentIntegration
 * @group Components
 */
class StripePaymentIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function authoriseWithStripButtonShows()
    {
        $merchantOne = $this->createAndReturnMerchant(['registration_stage' => 0]);

        $stripeService = app()->make(StripeContract::class);

        $component = new StripePaymentIntegration($merchantOne, $stripeService);

        $this->assertStringContainsString('Connect with Stripe', $component->render());
    }

    /**
     * @test
     */
    public function deauthoriseStripeButtonShows()
    {
        $merchantOne = $this->createAndReturnMerchant(['registration_stage' => 0]);

        $stripeService = app()->make(StripeContract::class);

        $this->createAndReturnPaymentProvider();

        $merchantOne->paymentProviders()->attach(1, ['data' => json_encode(['test' => 'test'])]);

        $component = new StripePaymentIntegration($merchantOne, $stripeService);

        $this->assertStringContainsString('Deauthorize Stripe Account', $component->render());
    }
}
