<?php

namespace Tests\Unit\Service;

use App\Contract\Repositories\OrderContract;
use App\Contract\Service\SearchOrderContract;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class AboutHowItWorksControllerTest
 * @package Tests\Services
 * @coversDefaultClass \App\Providers\Service\SearchOrderServiceProvider
 */
class SearchOrderServiceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @var SearchOrderContract|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private $searchService;

    /**
     * @var string[]
     */
    private array $orderRecords;

    /**
     * @var OrderContract|mixed
     */
    private $orderRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->searchService = $this->app->make(SearchOrderContract::class);
        $this->orderRepository = $this->app->make(OrderContract::class);
    }

    /**
     * Searching by name field returns correct record
     * @test
     * @return void
     */
    public function searchByEmail()
    {
        $user = factory(User::class)->create();
        $merchantOne = $this->createAndReturnMerchant(['registration_stage' => 3]);
        $user->merchants()->attach($merchantOne->id);

        $createdOrders = [];
        $populatedEmails = [];

        for ($i = 0; $i < 3; $i++) {
            $email = $this->faker->safeEmail;
            $createdOrders[] = $this->createAndReturnOrderForStatus('Purchased Order', [
                'merchant_id' => $merchantOne->id,
                'customer_email' => $email
            ]);
            $populatedEmails[] = $email;
        }

        $this->assertDatabaseHas(
            'orders',
            [
                'customer_email' => $populatedEmails[0],
            ]
        );

        $searchedOrders = $this->searchService->searchOrdersByStringAndMerchant(
            $populatedEmails[0],
            $merchantOne->id
        );

        $this->assertCount(1, $searchedOrders);
        $this->assertEquals($populatedEmails[0], $searchedOrders->first()->customer_email);
    }
}
