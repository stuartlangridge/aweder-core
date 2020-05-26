<?php

namespace App\Contract\Service;

use App\Inventory;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface InventoryContract
{
    /**
     * Uploads an image file and attaches the path from S3 to the inventory
     *
     * @param UploadedFile $file
     * @param Inventory $inventoryItem
     * @return bool
     */
    public function uploadImageForInventory(UploadedFile $file, Inventory $inventoryItem): bool;
}
