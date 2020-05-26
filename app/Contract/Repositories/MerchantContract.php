<?php

namespace App\Contract\Repositories;

use App\Merchant;
use App\User;

interface MerchantContract
{
    /**
     * @param User $user
     * @param array $registrationData
     * @return Merchant | null
     */
    public function createMerchant(User $user, array $registrationData = []): ?Merchant;

    /**
     * @param Merchant $merchant
     * @param array $registrationData
     *
     * @return bool
     */
    public function updateMerchant(Merchant $merchant, array $registrationData = []): bool;

    /**
     * returns a merchant by url slug
     * @param string $urlSlug
     * @return Merchant|null
     */
    public function getMerchantByUrlSlug(string $urlSlug): ?Merchant;
}
