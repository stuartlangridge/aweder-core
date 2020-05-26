<?php

namespace Tests\Unit\View\Components;

use App\View\Components\DisplayItem;
use Tests\TestCase;

/**
 * Class DisplayItemTest
 * @package Tests\Unit\ViewComponents\Store
 * @coversDefaultClass \App\View\Components\Store\DisplayItem
 * @group Component
 */
class DisplayItemTest extends TestCase
{
    /**
     * @test
     */
    public function itemDescriptionIsVisible()
    {
        $merchant = $this->createAndReturnMerchant();

        $inventory = $this->createAndReturnInventoryItem(
            [
                'merchant_id' => $merchant->id,
                'description' => 'cottage pie of delight'
            ]
        );

        $component = new DisplayItem($inventory, $merchant, true, null);

        $this->assertStringContainsString('cottage pie of delight', $component->render());
    }

    /**
     * @test
     */
    public function orderSlugDoesAppearInHiddenInput()
    {
        $merchant = $this->createAndReturnMerchant();

        $inventory = $this->createAndReturnInventoryItem(
            [
                'merchant_id' => $merchant->id,
                'description' => 'cottage pie of delight'
            ]
        );

        $order = $this->createAndReturnOrderForStatus('Incomplete Order', ['merchant_id' => $merchant->id]);

        $this->createAndReturnOrderItem(['order_id' => $order->id ]);

        $component = new DisplayItem($inventory, $merchant, true, $order);

        $this->assertStringContainsString('cottage pie of delight', $component->render());

        $hiddenInput = '<input type="hidden" name="order_no" value="' . $order->url_slug. '" />';

        $this->assertStringContainsString($hiddenInput, $component->render());
    }
}
