<?php

namespace Tests\Unit\Repository;

use App\Contract\Repositories\UserContract;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class UserRepositoryTest
 * @package Tests\Unit\Repository
 * @group User
 */
class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @var UserContract
     */
    protected $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(UserContract::class);
    }

    /**
     * @test
     * makes sure the user is created test
     */
    public function createsAUserInTheDB()
    {
        $email =  $this->faker->safeEmail;

        $userData = [
            'email' => $email,
            'password' => $this->faker->password
        ];

        $result = $this->repository->createUser($userData);

        $this->assertInstanceOf(User::class, $result);

        $this->assertDatabaseHas(
            'users',
            [
                'email' => $email
            ]
        );
    }
}
