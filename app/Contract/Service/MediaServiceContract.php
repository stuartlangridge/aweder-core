<?php

namespace App\Contract\Service;

use Illuminate\Http\UploadedFile;

interface MediaServiceContract
{
    /**
     * uploads a new file to the filesystem currently being used and returns the filepath and filename
     * @param UploadedFile $file
     * @param string $folder
     * @return string|null
     */
    public function uploadMediaItemToCloud(UploadedFile $file, string $folder): ?string;

    /**
     * removes the filename provided from the cloud provider
     * @param string $fileNameWithPath
     * @return bool
     */
    public function removeMediaItemFromCloud(string $fileNameWithPath): bool;
}
