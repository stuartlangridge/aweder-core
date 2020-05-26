<?php

namespace Tests\Unit\Model;

use App\Order;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class OrderTest
 * @package Tests\Unit\Model
 * @group Order
 * @group OrderModel
 * @coversDefaultClass \App\Order
 */
class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Order
     */
    protected $model;

    public function setUp(): void
    {
        parent::setUp();

        $this->model = app()->make(Order::class);
    }

    /**
     * @test
     * @dataProvider deliveryOrCollectionProvider
     */
    public function getIsDeliveryOrCollectionWithValidTypes($bool, $type): void
    {
        $this->model->__set('is_delivery', $bool);

        $value = $this->model->getIsDeliveryOrCollection();

        $this->assertEquals($type, $value);
    }

    /**
     * @test
     */
    public function timeSinceCreatedWithCreatedAtOlderThan20Minutes(): void
    {
        $now = Carbon::create(2020, 03, 26, 10, 37, 35);
        Carbon::setTestNow($now);
        $this->model->__set('order_submitted', Carbon::create(2020, 03, 26, 10, 17, 35));
        $response = $this->model->getTimeSinceCreatedAndIfTheOrderIsOlderThan20Minutes();
        $this->assertIsArray($response);
        $this->assertTrue($response['old']);
        $this->assertEquals('20:00', $response['time']);
        $this->assertEquals('20', $response['time_to_display']);
    }

    /**
     * @test
     */
    public function timeSinceCreatedWithCreatedAtLessThan20Minutes(): void
    {
        $now = Carbon::create(2020, 03, 26, 10, 37, 35);
        Carbon::setTestNow($now);
        $this->model->__set('order_submitted', Carbon::create(2020, 03, 26, 10, 32, 35));
        $response = $this->model->getTimeSinceCreatedAndIfTheOrderIsOlderThan20Minutes();
        $this->assertIsArray($response);
        $this->assertFalse($response['old']);
        $this->assertEquals('05:00', $response['time']);
        $this->assertEquals('05', $response['time_to_display']);
    }

    public function deliveryOrCollectionProvider(): array
    {
        return [
            [true, 'Delivery'],
            [false, 'Collection'],
        ];
    }

    /**
     * @param string $statusToSet
     * @param string $expectedFrontEndStatus
     * @test
     * @dataProvider frontendStatuses
     */
    public function frontendStatusResults(string $statusToSet, string $expectedFrontEndStatus): void
    {
        $order = $this->createAndReturnOrderForStatus('Unprocessed Order', ['status' => $statusToSet]);

        $this->assertSame($expectedFrontEndStatus, $order->getNiceFrontendStatus());
    }


    /**
     * @return array|array[]
     */
    public function frontendStatuses(): array
    {
        return [
            'Acknowledged' => [
                'acknowledged',
                'Processing',
            ],
            'Processing' => [
                'processing',
                'Processing',
            ],
            'Purchased' => [
                'purchased',
                'New Order',
            ],
            'Fulfilled' => [
                'fulfilled',
                'Completed',
            ],
            'Rejected' => [
                'rejected',
                'Rejected',
            ],
        ];
    }
}
