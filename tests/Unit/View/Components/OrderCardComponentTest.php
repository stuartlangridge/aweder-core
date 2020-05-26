<?php

namespace Tests\Unit\View\Components;

use App\View\Components\OrderCardComponent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class OrderCardComponentTest
 * @package Tests\Unit\View\Components
 * @coversDefaultClass \App\View\Components\OrderCardComponent
 * @group Component
 */
class OrderCardComponentTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * @test
     */
    public function acknowledgedCollectionCardDisplaysAsExpected()
    {
        $merchantOne = $this->createAndReturnMerchant(['registration_stage' => 0]);

        $order = $this->createAndReturnOrderForStatus(
            'Acknowledged Order',
            [
                'merchant_id' => $merchantOne->id,
                'is_delivery' => 0,
            ]
        );

        $component = new OrderCardComponent($order);

        $this->assertStringContainsString('Collection', $component->render());

        $this->assertStringContainsString('Mark as fulfilled', $component->render());

        $this->assertStringContainsString(
            '&pound;' . $order->getFormattedUKPriceAttribute($order->total),
            $component->render()
        );
    }

    /**
     * @test
     */
    public function purchasedCardDisplaysAsExcepted()
    {
        $merchantOne = $this->createAndReturnMerchant(['registration_stage' => 0]);

        $order = $this->createAndReturnOrderForStatus(
            'Purchased Order',
            [
                'merchant_id' => $merchantOne->id,
                'is_delivery' => 0,
            ]
        );

        $component = new OrderCardComponent($order);

        $this->assertStringContainsString('Collection', $component->render());

        $this->assertStringNotContainsString('Mark as fulfilled', $component->render());

        $this->assertStringContainsString(
            '&pound;' . $order->getFormattedUKPriceAttribute($order->total),
            $component->render()
        );
    }

    /**
     * @test
     */
    public function purchasedCardWithDeliveryDisplaysAsExpected()
    {
        $merchantOne = $this->createAndReturnMerchant(['registration_stage' => 0]);

        $order = $this->createAndReturnOrderForStatus(
            'Purchased Order',
            [
                'merchant_id' => $merchantOne->id,
                'is_delivery' => 1,
            ]
        );

        $component = new OrderCardComponent($order);

        $this->assertStringContainsString('Delivery', $component->render());

        $this->assertStringNotContainsString('Mark as fulfilled', $component->render());

        $this->assertStringContainsString(
            '&pound;' . $order->getFormattedUKPriceAttribute($order->total, $order->delivery_cost),
            $component->render()
        );
    }
}
