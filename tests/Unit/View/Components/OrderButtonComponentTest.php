<?php

namespace Tests\Unit\View\Components;

use App\View\Components\OrderButtonComponent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class OrderButtonComponentTest
 * @package Tests\Unit\View\Components
 * @coversDefaultClass \App\View\Components\OrderButtonComponent
 * @group Component
 */
class OrderButtonComponentTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * @test
     */
    public function buttonShowsNothingWhenStatusIsNotFulfilled()
    {
        $merchantOne = $this->createAndReturnMerchant(['registration_stage' => 0]);

        $order = $this->createAndReturnOrderForStatus('Purchased Order', ['merchant_id' => $merchantOne->id]);

        $component = new OrderButtonComponent($order);

        $this->assertStringNotContainsString('Mark as fulfilled', $component->render());
    }

    /**
     * @test
     */
    public function buttonShowsNothingWhenStatusIsAcknowledged()
    {
        $merchantOne = $this->createAndReturnMerchant(['registration_stage' => 0]);

        $order = $this->createAndReturnOrderForStatus('Acknowledged Order', ['merchant_id' => $merchantOne->id]);

        $component = new OrderButtonComponent($order);

        $this->assertStringContainsString('Mark as fulfilled', $component->render());
    }
}
