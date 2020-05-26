<?php

namespace App\Contract\Service;

use App\Merchant;
use App\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface RegistrationContract
{
    /**
     * @param array $userDetails
     * @return User|null
     */
    public function createNewUser(array $userDetails = []): ?User;

    /**
     * @param User $user
     * @param array $merchant
     * @return Merchant|null
     */
    public function createMerchantForUser(User $user, array $merchant = []): ?Merchant;

    /**
     * registers a new Merchant in the system
     * @param array $registrationData
     * @return User | null
     */
    public function createNewUserAndMerchant(array $registrationData = []): ?User;

    /**
     * @param Merchant $merchant
     * @param array $data
     *
     * @return bool
     */
    public function updateMerchant(Merchant $merchant, array $data = []): bool;

    /**
     * @param UploadedFile $file
     * @param Merchant $merchant
     *
     * @return bool
     */
    public function uploadFileLogoForMerchant(UploadedFile $file, Merchant $merchant): bool;
}
