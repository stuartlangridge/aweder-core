<?php

namespace App\Service;

use App\Contract\Repositories\MerchantContract;
use App\Contract\Repositories\UserContract;
use App\Contract\Service\RegistrationContract;
use App\Merchant;
use App\User;
use Illuminate\Filesystem\FilesystemManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class RegistrationService implements RegistrationContract
{
    protected MerchantContract $merchantRepository;

    protected UserContract $userRepository;

    protected LoggerInterface $logger;

    protected FilesystemManager $fileSystemManager;

    public function __construct(
        MerchantContract $merchantRepository,
        UserContract $userRepository,
        LoggerInterface $logger,
        FilesystemManager $filesystemManager
    ) {
        $this->merchantRepository = $merchantRepository;

        $this->userRepository = $userRepository;

        $this->logger = $logger;

        $this->fileSystemManager = $filesystemManager;
    }

    public function createNewUser(array $userDetails = []): ?User
    {
        try {
            return $this->createUser($userDetails);
        } catch (\Exception $e) {
            $this->logger->error('RegistrationService::createNewUserAndMerchant ' . $e->getMessage());
            return null;
        }
    }

    public function createMerchantForUser(User $user, array $merchant = []): ?Merchant
    {
        return $this->createMerchant($user, $merchant);
    }

    public function createNewUserAndMerchant(array $registrationData = []): ?User
    {
        try {
            $user = $this->createUser($registrationData);

            $merchant = $this->createMerchant($user, $registrationData);

            $user->merchants()->attach($merchant->id);

            return $user;
        } catch (\Exception $e) {
            $this->logger->error('RegistrationService::createNewUserAndMerchant ' . $e->getMessage());
            return null;
        }
    }

    /**
     * creates a user
     * @param array $userData
     * @return User
     */
    protected function createUser(array $userData = []): User
    {
        return $this->userRepository->createUser($userData);
    }

    /**
     * @param User $user
     * @param array $merchantData
     * @return Merchant
     */
    protected function createMerchant(User $user, array $merchantData = []): Merchant
    {
        return $this->merchantRepository->createMerchant($user, $merchantData);
    }

    /**
     * @param Merchant $merchant
     * @param array $data
     *
     * @return bool
     */
    public function updateMerchant(Merchant $merchant, array $data = []): bool
    {
        return $this->merchantRepository->updateMerchant($merchant, $data);
    }

    public function uploadFileLogoForMerchant(UploadedFile $file, Merchant $merchant): bool
    {
        if (!$path = $this->fileSystemManager->cloud()->putFile('merchants', $file)) {
            return false;
        }

        return $merchant->update(['logo' => $path]);
    }
}
