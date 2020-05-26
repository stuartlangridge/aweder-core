<?php

namespace App\Contract\Repositories;

use App\User;

interface UserContract
{
    /**
     * creates a user
     * @param array $userData
     * @return User
     */
    public function createUser(array $userData = []): User;

    /**
     * Gets the user and their merchants
     * @param int $userId
     * @return User
     */
    public function getUserWithMerchants(int $userId): User;
}
