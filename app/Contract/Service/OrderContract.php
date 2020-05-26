<?php

namespace App\Contract\Service;

use App\Merchant;
use App\Order;

interface OrderContract
{
    /**
     * checks if the current item does belong to the merchant
     * @param int $merchantId
     * @param int $itemId
     * @return bool
     */
    public function doesItemBelongToMerchant(int $merchantId, int $itemId): bool;

    /**
     * @param Merchant $merchant
     * @return Order
     */
    public function createNewOrderForMerchant(Merchant $merchant): Order;

    /**
     * @param Merchant $merchant
     * @param string $orderNo
     * @return Order
     */
    public function retrieveCurrentOrderForMerchant(Merchant $merchant, string $orderNo): Order;

    /**
     * @param Order $order
     * @param Merchant $merchant
     * @param int $itemId
     * @return bool
     */
    public function addItemToOrder(Order $order, Merchant $merchant, int $itemId): bool;

    /**
     * @param Order $order
     * @param Merchant $merchant
     * @param int $itemId
     * @return bool
     */
    public function removeItemFromOrder(Order $order, Merchant $merchant, int $itemId): bool;

    /**
     * @param Order $order
     * @param int $itemId
     * @return bool
     */
    public function doesOrderAlreadyContainItem(Order $order, int $itemId): bool;

    /**
     * @param Order $order
     */
    public function updateOrderTotal(Order $order): void;

    /**
     * method to check if the current order in question has previously submitted
     * @param Order $order
     * @return bool
     */
    public function hasOrderBeenPreviouslySubmitted(Order $order): bool;

    /**
     * @param Order $order
     * @param string $newStatusToChangeTo
     * @return bool
     */
    public function hasOrderGonePastStage(Order $order, string $newStatusToChangeTo): bool;

    /**
     * @param Order $order
     * @param string $orderStatus
     * @return bool
     */
    public function updateOrderStatus(Order $order, string $orderStatus): bool;

    /**
     * @param Order $order
     * @param string $customerNote
     */
    public function attachCustomerNoteToOrder(Order $order, string $customerNote): void;

    /**
     * @param Order $order
     * @param string $rejectReason
     *
     * @return bool
     */
    public function attachMerchantNoteToOrder(Order $order, string $rejectReason): bool;

    /**
     * @param Order $order
     * @param array $customerDetails
     * @return bool
     */
    public function storeCustomerDetailsOnOrder(Order $order, array $customerDetails = []): bool;

    /**
     * checks to see if the current order belongs to the merchant
     * @param Order $order
     * @param Merchant $merchant
     * @return bool
     */
    public function doesOrderBelongToMerchant(Order $order, Merchant $merchant): bool;
}
