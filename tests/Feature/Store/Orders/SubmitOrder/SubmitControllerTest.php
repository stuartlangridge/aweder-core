<?php

namespace Tests\Feature\Store\Orders\SubmitOrder;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class OrderDetailsControllerTest
 * @package Tests\Feature\Store\Orders
 * @coversDefaultClass \App\Http\Controllers\Store\Orders\SubmitController
 * @group Orders
 */
class SubmitControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     */
    public function cannotSubmitDeliveryToCollectionOnlyMerchant()
    {
        $merchant = $this->createAndReturnMerchant(['allow_delivery' => 0]);
        $order = $this->createAndReturnOrderForStatus('Incomplete Order', ['merchant_id' => $merchant->id]);

        $postSubmitRoute = route(
            'store.order.submit',
            [
                'merchant' => $merchant->url_slug,
                'order' => $order->url_slug
            ]
        );

        $submitRoute = route(
            'store.order.submit',
            [
                'merchant' => $merchant->url_slug,
                'order' => $order->url_slug
            ]
        );

        $postDetails = [
            'collection_type' => 'delivery'
        ];

        $response = $this->from($submitRoute)->post($postSubmitRoute, $postDetails);

        $response->assertRedirect($submitRoute);

        $response->assertSessionHasErrors('collection_type');
    }
}
