<?php

namespace App\Service;

use App\Contract\Service\MediaServiceContract;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\UploadedFile;
use Psr\Log\LoggerInterface;

class MediaService implements MediaServiceContract
{
    protected FilesystemManager $fileSystemManager;

    protected LoggerInterface $logger;

    public function __construct(
        FilesystemManager $filesystemManager,
        LoggerInterface $logger
    ) {
        $this->fileSystemManager = $filesystemManager;

        $this->logger = $logger;
    }

    public function uploadMediaItemToCloud(UploadedFile $file, string $folder): ?string
    {
        if (!$path = $this->fileSystemManager->cloud()->putFile($folder, $file)) {
            return null;
        }

        return $path;
    }

    public function removeMediaItemFromCloud(string $fileNameWithPath): bool
    {
        return $this->fileSystemManager->cloud()->delete($fileNameWithPath);
    }
}
