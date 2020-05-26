<?php

namespace Tests\Unit\Repository;

use App\Contract\Repositories\OrderContract;
use App\Inventory;
use App\Merchant;
use App\Order;
use App\OrderItem;
use App\OrderReminder;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class OrderRepositoryTest
 * @package Tests\Unit\Repository
 * @coversDefaultClass \App\Repository\OrderRepository;
 * @group Order
 */
class OrderRepositoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @var OrderContract
     */
    protected $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(OrderContract::class);
    }

    /**
     * @test
     */
    public function onlyReturnsUnprocessedOrdersInCreatedOrderForSpecifiedTimePeriod(): void
    {
        $unprocessedOrder = factory(Order::class)->state('Unprocessed Order')->create([
            'created_at' => Carbon::parse()->subMinutes(30),
            'order_submitted' => Carbon::parse()->subMinutes(30),
        ]);

        $secondUnProcessedOrder = factory(Order::class)->state('Unprocessed Order')->create([
            'order_submitted' => Carbon::parse()->subMinutes(40),
            'created_at' => Carbon::parse()->subMinutes(40),
        ]);

        factory(Order::class)->state('Incomplete Order')->create();

        factory(Order::class)->state('Unprocessed Order')->create([
            'created_at' => Carbon::parse()->subMinutes(10),
            'order_submitted' => Carbon::parse()->subMinutes(10),
        ]);

        $unprocessedOrders = $this->repository->getUnprocessedOrdersBetweenPeriod(
            Carbon::parse()->subMinutes(60),
            Carbon::parse()->subMinutes(30)
        );

        $this->assertCount(2, $unprocessedOrders);

        $this->assertEquals($secondUnProcessedOrder->id, $unprocessedOrders->first()->id);

        $this->assertEquals($unprocessedOrder->id, $unprocessedOrders->last()->id);
    }

    /**
     * @test
     * checks that a blank order is saved on creation
     */
    public function savesIncompleteStatusForNewOrder(): void
    {
        $status = 'incomplete';

        $merchant = $this->createAndReturnMerchant();

        $order = $this->repository->createEmptyOrderWithStatus($merchant->id, $status);

        $this->assertSame($status, $order->status);
    }

    /**
     * @test
     */
    public function makeSureItemQuantityIsUpdated(): void
    {
        $order = factory(Order::class)->create();

        $inventory = factory(Inventory::class)->create();

        $orderItem = new OrderItem(
            [
                'inventory_id' => $inventory->id,
                'quantity' => 1,
            ]
        );

        $order->items()->save($orderItem);

        $this->repository->updateQuantityOnItemInOrder($order, $inventory->id);

        $this->assertDatabaseHas(
            'order_items',
            [
                'inventory_id' => $inventory->id,
                'order_id' => $order->id,
                'quantity' => 2,
            ]
        );
    }

    /**
     * checks that various order submission states are ok
     * @test
     * @dataProvider submittedOrders
     * @param Order $order
     * @param bool $expectedStatus
     * @group OrderStatus
     */
    public function orderPreviouslySubmittedCheck(string $scope, bool $expectedStatus): void
    {
        $order = factory(Order::class)->state($scope)->create();

        $this->assertSame($expectedStatus, $this->repository->hasOrderBeenPreviouslySubmitted($order));
    }

    /**
     * @test
     * @dataProvider statusDataProvider
     * @param $status
     */
    public function getOrdersByMerchantAndStatusReturnsOrdersByMerchantUsingStatus($status): void
    {
        $merchant = factory(Merchant::class)->create();

        factory(Order::class, 5)->create([
            'merchant_id' => $merchant->id,
            'status' => $status
        ]);

        $orders = $this->repository->getOrdersByMerchantAndStatus($merchant->id, $status, 'DESC');

        foreach ($orders as $order) {
            $this->assertEquals($order->status, $status);
        }
    }

    /**
     * @test
     * @group UnprocessedOrders
     */
    public function getUnprocessedOrdersBetweenPeriodWhereNoRemindersHaveBeenSentForTimeWithNoOrderRemindersSet(): void
    {
        $merchant = factory(Merchant::class)->create();

        $minutes = 20;

        $start = Carbon::now()->subMinutes($minutes + 1)->addSecond();
        $end = Carbon::now()->subMinutes($minutes);

        $order = factory(Order::class)->state('Unprocessed Order')->create([
            'created_at' => Carbon::parse()->subMinutes($minutes),
            'order_submitted' => Carbon::parse()->subMinutes($minutes),
            'merchant_id' => $merchant->id,
        ]);

        $orders = $this->repository->getUnprocessedOrdersBetweenPeriodWhereNoRemindersHaveBeenSentForTime(
            $start,
            $end,
            $minutes
        );

        $this->assertCount(1, $orders);
    }

    /**
     * @test
     * @group UnprocessedOrders
     */
    public function getUnprocessedOrdersBetweenPeriodWhereNoRemindersHaveBeenSentForTimeWithOrderRemindersSet(): void
    {
        $merchant = factory(Merchant::class)->create();

        $minutes = 20;

        $start = Carbon::now()->subMinutes($minutes + 1)->addSecond();
        $end = Carbon::now()->subMinutes($minutes);

        $order = factory(Order::class)->state('Unprocessed Order')->create([
            'created_at' => Carbon::parse()->subMinutes($minutes),
            'order_submitted' => Carbon::parse()->subMinutes($minutes),
            'merchant_id' => $merchant->id,
        ]);

        factory(OrderReminder::class)->create(
            [
                'order_id' => $order->id,
                'reminder_time' => $minutes,
                'sent' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        );

        $orders = $this->repository->getUnprocessedOrdersBetweenPeriodWhereNoRemindersHaveBeenSentForTime(
            $start,
            $end,
            $minutes
        );

        $this->assertCount(0, $orders);
    }

    /**
     * @test
     * @group UnprocessedOrders
     */
    public function getUnprocessedOrdersBetweenPeriodWhereNoRemindersHaveBeenSentForTimeWithMixedOrderRemindersSet(): void
    {
        $merchant = factory(Merchant::class)->create();

        $minutes = 20;

        $start = Carbon::now()->subMinutes($minutes + 1)->addSecond();
        $end = Carbon::now()->subMinutes($minutes);

        $order = factory(Order::class)->state('Unprocessed Order')->create([
            'created_at' => Carbon::parse()->subMinutes($minutes),
            'order_submitted' => Carbon::parse()->subMinutes($minutes),
            'merchant_id' => $merchant->id,
        ]);

        factory(OrderReminder::class)->create(
            [
                'order_id' => $order->id,
                'reminder_time' => $minutes,
                'sent' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        );

        $order = factory(Order::class)->state('Unprocessed Order')->create([
            'created_at' => Carbon::parse()->subMinutes($minutes),
            'order_submitted' => Carbon::parse()->subMinutes($minutes),
            'merchant_id' => $merchant->id,
        ]);

        $orders = $this->repository->getUnprocessedOrdersBetweenPeriodWhereNoRemindersHaveBeenSentForTime(
            $start,
            $end,
            $minutes
        );

        $this->assertCount(1, $orders);
    }

    /**
     * @test
     * @group UnprocessedOrders
     */
    public function getUnprocessedOrdersBetweenPeriodWhereNoRemindersHaveBeenSentForTimeWithVariousOrdersTimeSlots(): void
    {
        $merchant = factory(Merchant::class)->create();

        $minutes = 20;

        $start = Carbon::now()->subMinutes($minutes + 1)->addSecond();
        $end = Carbon::now()->subMinutes($minutes);

        $order = factory(Order::class)->state('Unprocessed Order')->create([
            'created_at' => Carbon::parse()->subMinutes($minutes),
            'order_submitted' => Carbon::parse()->subMinutes($minutes),
            'merchant_id' => $merchant->id,
        ]);

        factory(OrderReminder::class)->create(
            [
                'order_id' => $order->id,
                'reminder_time' => $minutes,
                'sent' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        );

        $orderWayBefore = factory(Order::class)->state('Unprocessed Order')->create([
            'created_at' => Carbon::parse()->subMinutes($minutes - 50),
            'order_submitted' => Carbon::parse()->subMinutes($minutes - 50),
            'merchant_id' => $merchant->id,
        ]);

        $orderNewer = factory(Order::class)->state('Unprocessed Order')->create([
            'created_at' => Carbon::parse()->subMinutes($minutes + 10),
            'order_submitted' => Carbon::parse()->subMinutes($minutes + 10),
            'merchant_id' => $merchant->id,
        ]);

        $orders = $this->repository->getUnprocessedOrdersBetweenPeriodWhereNoRemindersHaveBeenSentForTime(
            $start,
            $end,
            $minutes
        );

        $this->assertCount(0, $orders);
    }

    /**
     * @test
     * @group UnprocessedOrders
     */
    public function getUnprocessedOrdersBetweenPeriodWhereNoRemindersHaveBeenSentForTimeWithVariousOrdersTimeSlotsOne(): void
    {
        $merchant = factory(Merchant::class)->create();

        $minutes = 19;

        $start = Carbon::now()->subMinutes($minutes + 1)->addSecond();
        $end = Carbon::now()->subMinutes($minutes);

        $orderWithReminder = factory(Order::class)->state('Unprocessed Order')->create([
            'created_at' => Carbon::parse()->subMinutes($minutes),
            'order_submitted' => Carbon::parse()->subMinutes($minutes),
            'merchant_id' => $merchant->id,
        ]);

        factory(OrderReminder::class)->create(
            [
                'order_id' => $orderWithReminder->id,
                'reminder_time' => $minutes,
                'sent' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        );

        $orderWayBefore = factory(Order::class)->state('Unprocessed Order')->create([
            'created_at' => Carbon::parse()->subMinutes($minutes - 50),
            'order_submitted' => Carbon::parse()->subMinutes($minutes - 50),
            'merchant_id' => $merchant->id,
        ]);

        $orderNewer = factory(Order::class)->state('Unprocessed Order')->create([
            'created_at' => Carbon::parse()->subMinutes($minutes + 10),
            'order_submitted' => Carbon::parse()->subMinutes($minutes + 10),
            'merchant_id' => $merchant->id,
        ]);

        $order = factory(Order::class)->state('Unprocessed Order')->create([
            'created_at' => Carbon::parse()->subMinutes($minutes),
            'order_submitted' => Carbon::parse()->subMinutes($minutes),
            'merchant_id' => $merchant->id,
        ]);

        $orders = $this->repository->getUnprocessedOrdersBetweenPeriodWhereNoRemindersHaveBeenSentForTime(
            $start,
            $end,
            $minutes
        );

        $this->assertCount(1, $orders);
    }

    /**
     * @test
     */
    public function onlyReturnsValidOrdersInStatusChecks(): void
    {
        $merchant = factory(Merchant::class)->create();

        $order = $this->createAndReturnOrderForStatus('Rejected Order', ['merchant_id' => $merchant->id]);

        $result = $this->repository->getOrdersByMerchantAndStatuses(
            $merchant->id,
            ['purchased'],
            'ASC',
            Carbon::now()->startOfYear(),
            Carbon::now()->endOfYear()
        );

        $this->assertTrue($result->isEmpty());
    }

    /**
     * @test
     */
    public function noOrdersReturnedForDifferentMerchant(): void
    {
        $merchant = factory(Merchant::class)->create();

        $merchantTwo = factory(Merchant::class)->create();

        $order = $this->createAndReturnOrderForStatus('Rejected Order', ['merchant_id' => $merchant->id]);

        $result = $this->repository->getOrdersByMerchantAndStatuses(
            $merchantTwo->id,
            ['purchased'],
            'ASC',
            Carbon::now()->startOfYear(),
            Carbon::now()->endOfYear()
        );

        $this->assertTrue($result->isEmpty());
    }

    /**
     * @test
     */
    public function getDashboardMetricsForMerchantWithOrders(): void
    {
        $merchant = factory(Merchant::class)->create();
        $fulfilledOrders = random_int(1, 15);
        $processingOrders = random_int(1, 20);

        factory(Order::class, $fulfilledOrders)->create([
            'merchant_id' => $merchant->id,
            'status' => 'fulfilled'
        ]);

        factory(Order::class, $processingOrders)->create([
            'merchant_id' => $merchant->id,
            'status' => 'processing'
        ]);

        $orderRepository = $this->app->make(OrderContract::class);
        $dashboardMetrics = $orderRepository->getDashboardStatisticsForMerchantWithDateRange(
            $merchant->id,
            'this-month'
        );
        $this->assertArrayHasKey('fulfilled', $dashboardMetrics);
        $this->assertEquals($fulfilledOrders, $dashboardMetrics['fulfilled']);
    }

    /**
     * @test
     */
    public function getTransformedDashboardMetricsForMerchant(): void
    {
        $merchant = factory(Merchant::class)->create();
        $acknowledgedOrders = random_int(1, 15);
        $processingOrders = random_int(1, 20);

        factory(Order::class, $acknowledgedOrders)->create([
            'merchant_id' => $merchant->id,
            'status' => 'processing'
        ]);

        factory(Order::class, $processingOrders)->create([
            'merchant_id' => $merchant->id,
            'status' => 'acknowledged'
        ]);

        $orderRepository = $this->app->make(OrderContract::class);
        $dashboardMetrics = $orderRepository->getFrontendStatisticsForMerchantWithDateRange(
            $merchant->id,
            'this-month'
        );
        $this->assertArrayHasKey('Processing', $dashboardMetrics);
        $this->assertEquals($acknowledgedOrders + $processingOrders, $dashboardMetrics['Processing']);
    }

    /**
     * @test
     */
    public function getTransformedDashboardMetricsFilteredByWeek(): void
    {
        $merchant = factory(Merchant::class)->create();
        $acknowledgedOrders = random_int(1, 15);
        $processingOrders = random_int(1, 20);

        factory(Order::class, $acknowledgedOrders)->create([
            'merchant_id' => $merchant->id,
            'status' => 'processing'
        ]);

        factory(Order::class, $processingOrders)->create([
            'merchant_id' => $merchant->id,
            'status' => 'acknowledged'
        ]);

        factory(Order::class)->create([
            'merchant_id' => $merchant->id,
            'status' => 'processing',
            'created_at' => Carbon::now()->subDays(8)
        ]);

        $orderRepository = $this->app->make(OrderContract::class);
        $dashboardMetrics = $orderRepository->getFrontendStatisticsForMerchantWithDateRange($merchant->id, 'this-week');
        $this->assertArrayHasKey('Processing', $dashboardMetrics);
        $this->assertEquals($acknowledgedOrders + $processingOrders, $dashboardMetrics['Processing']);
    }

    /**
     * @test
     */
    public function getDashboardMetricsForMerchantWithoutOrders(): void
    {
        $merchant = factory(Merchant::class)->create();
        $orderRepository = $this->app->make(OrderContract::class);
        $dashboardMetrics = $orderRepository->getDashboardStatisticsForMerchantWithDateRange(
            $merchant->id,
            'this-month'
        );

        $this->assertArrayNotHasKey('fulfilled', $dashboardMetrics);
    }

    /**
     * @test
     */
    public function checkUnknownStatusReturned(): void
    {
        $merchantOne = $this->createAndReturnMerchant(['registration_stage' => 0]);

        $order = $this->createAndReturnOrderForStatus('Purchased Order', ['merchant_id' => $merchantOne->id]);
        $order->status = 'nonsense';
        $order->save();

        $this->assertEquals($order->getNiceFrontendStatus(), $order->getUnknownStatus());
    }

    /*
     * @test
     */
    public function retrieveOnlyStatusOrdersRequested(): void
    {
        $merchant = factory(Merchant::class)->create();

        $merchantTwo = factory(Merchant::class)->create();

        $this->createAndReturnOrderForStatus('Rejected Order', ['merchant_id' => $merchant->id]);

        $this->createAndReturnOrderForStatus('Purchased Order', ['merchant_id' => $merchant->id]);

        $this->createAndReturnOrderForStatus('Payment Rejected', ['merchant_id' => $merchant->id]);

        $this->createAndReturnOrderForStatus('Acknowledged Order', ['merchant_id' => $merchant->id]);

        $this->createAndReturnOrderForStatus('Rejected Order', ['merchant_id' => $merchantTwo->id]);

        $this->createAndReturnOrderForStatus('Purchased Order', ['merchant_id' => $merchantTwo->id]);

        $this->createAndReturnOrderForStatus('Payment Rejected', ['merchant_id' => $merchantTwo->id]);

        $this->createAndReturnOrderForStatus('Acknowledged Order', ['merchant_id' => $merchantTwo->id]);

        $result = $this->repository->getOrdersByMerchantAndStatuses(
            $merchant->id,
            [
                'purchased',
                'acknowledged',
            ],
            'ASC',
            Carbon::now()->startOfYear(),
            Carbon::now()->endOfYear()
        );

        $this->assertCount(2, $result);
    }

    public function statusDataProvider(): array
    {
        return [
            ['processing'],
            ['incomplete'],
            ['purchased'],
            ['ready-to-buy'],
            ['payment-rejected'],
            ['acknowledged'],
            ['rejected'],
            ['unacknowledged'],
        ];
    }


    public function submittedOrders(): array
    {
        return  [
            'incomplete' =>[
                'Incomplete Order',
                false,
            ],
            'purchased' =>[
                'Purchased Order',
                true,
            ],
            'rejected' =>[
                'Payment Rejected',
                true,
            ],
            'acknowledged' =>[
                'Acknowledged Order',
                true,
            ],
            'unacknowledged' =>[
                'Unacknowledged Order',
                true,
            ],
        ];
    }
}
