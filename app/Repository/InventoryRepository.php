<?php

namespace App\Repository;

use App\Contract\Repositories\InventoryContract;
use App\Traits\HelperTrait;
use App\Inventory;
use Psr\Log\LoggerInterface;
use Illuminate\Support\Facades\DB;

class InventoryRepository implements InventoryContract
{
    use HelperTrait;

    /**
     * @var Inventory
     */
    protected $model;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(Inventory $model, LoggerInterface $logger)
    {
        $this->model = $model;

        $this->logger = $logger;
    }

    /**
     * @param int $id
     * @return int
     */
    public function toggleInventoryItemStatusById(int $itemId): int
    {
        return $this->getModel()
            ->find($itemId)
            ->update(['available' => DB::raw('NOT available')]);
    }

    public function deleteInventoryItemById(int $itemId)
    {
        return $this->getModel()
            ->find($itemId)
            ->delete();
    }

    public function doesMerchantOwnItem(int $merchantId, int $itemId): bool
    {
        $item = $this->getModel()->where('id', $itemId)->where('merchant_id', $merchantId)->first();

        return $item instanceof Inventory;
    }

    public function getItemById(int $itemId): Inventory
    {
        return $this->getModel()->where('id', $itemId)->first();
    }

    public function createNewInventoryItemForMerchant(int $merchantId, array $inventoryDetails = []): Inventory
    {
        return $this->getModel()->create(
            [
                'category_id' => $inventoryDetails['category-id'],
                'merchant_id' => $merchantId,
                'title' => $inventoryDetails['title'],
                'price' => $this->convertToPence($inventoryDetails['amount']),
                'description' => $inventoryDetails['description']
            ]
        );
    }

    public function updateInventoryItem(
        Inventory $inventoryItem,
        array $inventoryDetails = []
    ): Inventory {
        $inventoryItem->update([
            'title' => $inventoryDetails['title'] ?? $inventoryItem->title,
            'description' => $inventoryDetails['description'] ?? $inventoryItem->description,
            'price' => isset($inventoryDetails['price']) ? $this->convertToPence($inventoryDetails['price'])
                : $inventoryItem->price,
        ]);

        return $inventoryItem;
    }

    protected function getModel(): Inventory
    {
        return $this->model;
    }
}
