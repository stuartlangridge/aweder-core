<?php

namespace Tests\Unit\Service;

use App\Contract\Service\StripeContract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class StripeServiceTest
 * @package Tests\Unit\Service
 * @coversDefaultClass \App\Service\StripeService
 * @group StripeService
 */
class StripeServiceTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    protected StripeContract $stripeService;

    public function setUp(): void
    {
        parent::setUp();
        $this->stripeService = app()->make(StripeContract::class);
    }

    /**
     * @test
     */
    public function noStripeIntentFoundReturnsNull()
    {
        $fakeStripeId = $this->faker->uuid;

        $result = $this->stripeService->retrievePaymentIntent($fakeStripeId, '123123');

        $this->assertNull($result);
    }
}
