<?php

namespace App\Service;

use App\Contract\Repositories\InventoryContract;
use App\Contract\Repositories\MerchantContract;
use App\Contract\Service\OrderContract;
use App\Contract\Repositories\OrderContract as OrderRepositoryContract;
use App\Merchant;
use App\Order;
use Psr\Log\LoggerInterface;

class OrderService implements OrderContract
{
    /**
     * @var OrderRepositoryContract
     */
    protected $orderRepository;

    /**
     * @var MerchantContract
     */
    protected $merchantRepository;

    /**
     * @var InventoryContract
     *
     */
    protected $inventoryRepository;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(
        OrderRepositoryContract $orderRepository,
        MerchantContract $merchantRepo,
        InventoryContract $inventoryRepo,
        LoggerInterface $logger
    ) {
        $this->orderRepository = $orderRepository;

        $this->merchantRepository = $merchantRepo;

        $this->inventoryRepository = $inventoryRepo;

        $this->logger = $logger;
    }

    public function doesItemBelongToMerchant(int $merchantId, int $itemId): bool
    {
        return $this->inventoryRepository->doesMerchantOwnItem($merchantId, $itemId);
    }

    public function createNewOrderForMerchant(Merchant $merchant): Order
    {
        return $this->orderRepository->createEmptyOrderWithStatus($merchant->id, 'incomplete');
    }

    public function retrieveCurrentOrderForMerchant(Merchant $merchant, string $orderNo): Order
    {
        return $this->orderRepository->retrieveOrderForMerchantByOrderNo($merchant->id, $orderNo);
    }

    public function addItemToOrder(Order $order, Merchant $merchant, int $itemId): bool
    {
        if (!$this->doesOrderAlreadyContainItem($order, $itemId)) {
            $inventoryItem = $this->inventoryRepository->getItemById($itemId);

            if ($inventoryItem->merchant_id === $merchant->id) {
                return $this->orderRepository->addItemToOder($order, $inventoryItem, 1);
            }

            return false;
        }

        return $this->orderRepository->updateQuantityOnItemInOrder($order, $itemId);
    }

    public function removeItemFromOrder(Order $order, Merchant $merchant, int $itemId): bool
    {
        $inventoryItem = $this->inventoryRepository->getItemById($itemId);

        if ($inventoryItem->merchant_id === $merchant->id) {
            return $this->orderRepository->removeItemFromOrder($order, $itemId);
        }

        return false;
    }

    public function doesOrderAlreadyContainItem(Order $order, int $itemId): bool
    {
        $order->load('items');

        if ($order->items->isEmpty()) {
            return false;
        }

        $found = false;

        foreach ($order->items as $item) {
            if ($itemId === $item->inventory_id) {
                $found = true;
            }
        }

        return $found;
    }

    public function updateOrderTotal(Order $order): void
    {
        $total = 0;

        $order->load(['items']);

        foreach ($order->items as $item) {
            $total += $item->price * $item->quantity;
        }

        $order->total_cost = $total;

        $order->save();
    }

    public function hasOrderBeenPreviouslySubmitted(Order $order): bool
    {
        return $this->orderRepository->hasOrderBeenPreviouslySubmitted($order);
    }

    public function hasOrderGonePastStage(Order $order, string $newStatusToChangeTo): bool
    {
        return $this->orderRepository->hasOrderGonePastStage($order, $newStatusToChangeTo);
    }

    public function updateOrderStatus(Order $order, string $orderStatus): bool
    {
        return $this->orderRepository->updateOrderStatus($order, $orderStatus);
    }

    public function attachCustomerNoteToOrder(Order $order, string $customerNote): void
    {
        $this->orderRepository->attachNoteToOrder($order, 'customer_note', $customerNote);
    }

    public function attachMerchantNoteToOrder(Order $order, string $rejectReason): bool
    {
        return $this->orderRepository->attachNoteToOrder($order, 'merchant_note', $rejectReason);
    }

    public function storeCustomerDetailsOnOrder(Order $order, array $customerDetails = []): bool
    {
        return $this->orderRepository->storeCustomerDetailsOnOrder($order, $customerDetails);
    }

    public function doesOrderBelongToMerchant(Order $order, Merchant $merchant): bool
    {
        return $order->merchant_id === $merchant->id;
    }
}
