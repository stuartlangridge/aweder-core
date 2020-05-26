<?php

namespace App\Contract\Repositories;

use App\Inventory;

interface InventoryContract
{
    /**
     * checks if the current merchant owns the item
     * @param int $merchantId
     * @param int $itemId
     * @return bool
     */
    public function doesMerchantOwnItem(int $merchantId, int $itemId): bool;

    /**
     * @param int $itemId
     * @return Inventory
     */
    public function getItemById(int $itemId): Inventory;

    /**
     * creates a new inventory item in the system
     * @param int $merchantId
     * @param array $inventoryDetails
     * @return Inventory
     */
    public function createNewInventoryItemForMerchant(int $merchantId, array $inventoryDetails = []): Inventory;

    /**
     * Toggle the status for the specified inventory item
     * @param int $itemId
     * @return int
     */
    public function toggleInventoryItemStatusById(int $itemId): int;

    /**
     * Deletes the inventory item corresponding to the id
     * @param int $itemId
     * @return mixed
     */
    public function deleteInventoryItemById(int $itemId);

    /**
     * Updates the given inventory item with the given array
     * @param Inventory $inventoryItem
     * @param array $descriptionDetails
     * @return Inventory
     */
    public function updateInventoryItem(
        Inventory $inventoryItem,
        array $descriptionDetails = []
    ): Inventory;
}
