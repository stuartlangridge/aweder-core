<?php

namespace App\Repository;

use App\Contract\Repositories\UserContract;
use App\User;
use Psr\Log\LoggerInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserContract
{
    /**
     * @var User
     */
    protected $model;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(User $model, LoggerInterface $logger)
    {
        $this->model = $model;

        $this->logger = $logger;
    }

    public function createUser(array $userData = []): User
    {
        return $this->getModel()->create(
            [
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
            ]
        );
    }

    public function getUserWithMerchants(int $userId): User
    {
        return $this->getModel()->query()->where(['id' => $userId])->with('merchants')->first();
    }

    /**
     * @return User
     */
    protected function getModel(): User
    {
        return $this->model;
    }
}
