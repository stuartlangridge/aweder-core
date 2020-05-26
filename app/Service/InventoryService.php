<?php

namespace App\Service;

use App\Contract\Service\InventoryContract;
use App\Inventory;
use Illuminate\Filesystem\FilesystemManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class InventoryService implements InventoryContract
{
    protected FilesystemManager $fileSystemManager;

    public function __construct(FilesystemManager $fileSystemManager)
    {
        $this->fileSystemManager = $fileSystemManager;
    }

    /**
     * @param UploadedFile $file
     * @param Inventory $inventoryItem
     * @return bool
     */
    public function uploadImageForInventory(UploadedFile $file, Inventory $inventoryItem): bool
    {
        if (!$path = $this->fileSystemManager->cloud()->putFile('inventory', $file)) {
            return false;
        }

        return $inventoryItem->update(['image' => $path]);
    }
}
