<?php

namespace App\Contract\Repositories;

use App\Inventory;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Order;

interface OrderContract
{
    /**
     * Gets all the on unprocessed between
     *
     * @param Carbon $start
     * @param Carbon $end
     *
     * @return Collection
     */
    public function getUnprocessedOrdersBetweenPeriod(Carbon $start, Carbon $end): Collection;

    /**
     * returns a list of unprocessed orders where they havent had a reminder sent out for the time requesteed
     * @param Carbon $start
     * @param Carbon $end
     * @param int $minutes
     * @return Collection
     */
    public function getUnprocessedOrdersBetweenPeriodWhereNoRemindersHaveBeenSentForTime(
        Carbon $start,
        Carbon $end,
        int $minutes
    ): Collection;

    /**
     * @param int $merchantId
     * @param string $orderNo
     * @return Order
     */
    public function retrieveOrderForMerchantByOrderNo(int $merchantId, string $orderNo): Order;

    /**
     * creates a new empty order in the system with a given status
     * @param int $merchantId
     * @param string $status
     * @return Order
     */
    public function createEmptyOrderWithStatus(int $merchantId, string $status): Order;

    /**
     * Set order status to be unacknowledged
     *
     * @param Order $order
     *
     * @return bool
     */
    public function setOrderToUnacknowledged(Order $order): bool;

    /**
     * Set order status to be acknowledged
     *
     * @param Order $order
     *
     * @return bool
     */
    public function setOrderToAcknowledged(Order $order): bool;

    /**
     * updates the available time on order
     *
     * @param Order $order
     * @param string $time
     *
     * @return bool
     */
    public function updateAvailableTimeOnOrder(Order $order, string $time): bool;

    /**
     * Used to filter order statuses in backend
     *
     * @param string $status
     * @return array
     */
    public function getBackendStatusesFromFrontendSearchStatus(string $status): array;

    /**
     * @param Order $order
     * @param int $itemId
     * @return bool
     */
    public function updateQuantityOnItemInOrder(Order $order, int $itemId): bool;

    /**
     * @param Order $order
     * @param Inventory $inventoryItem
     * @param int $quantity
     * @return bool
     */
    public function addItemToOder(Order $order, Inventory $inventoryItem, int $quantity): bool;

    /**
     * removes the order item from the DB
     * @param Order $order
     * @param int $itemId
     * @return bool
     */
    public function removeItemFromOrder(Order $order, int $itemId): bool;

    /**
     * checks if the order has previously been submitted
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
     * @param string $noteType
     * @param string $note
     *
     * @return bool
     */
    public function attachNoteToOrder(Order $order, string $noteType, string $note): bool;

    /**
     * @param int $merchantId
     * @param string $status
     * @param string $returnOrder
     *
     * @return mixed
     */
    public function getOrdersByMerchantAndStatus(int $merchantId, string $status, string $returnOrder): Collection;

    /**
     * @param int $merchantId
     * @param array $statuses
     * @param string $returnOrder
     * @param string $startDate
     * @param string $endDate
     * @return Collection
     */
    public function getOrdersByMerchantAndStatuses(
        int $merchantId,
        array $statuses,
        string $returnOrder,
        string $startDate,
        string $endDate
    ): Collection;

    /**
     * @param int $merchantId
     * @param string $status
     * @param string $returnOrder
     * @param int $limit
     * @param string $pagName
     * @return LengthAwarePaginator
     */
    public function getOrdersByMerchantAndStatsPaginated(
        int $merchantId,
        string $status,
        string $returnOrder,
        int $limit = 15,
        string $pagName = 'page'
    ): LengthAwarePaginator;

    /**
     * this stores the customer order details on the order.
     * @param Order $order
     * @param array $customerDetails
     * @return bool
     */
    public function storeCustomerDetailsOnOrder(Order $order, array $customerDetails = []): bool;

    /**
     * @param string $orderNo
     * @return Order|null
     */
    public function getOrderByOrderNo(string $orderNo): ?Order;

    /**
     * Wrapper for model that returns array of nicely formatted statuses & keys
     * @return array
     */
    public function getFormattedStatuses(): array;

    /**
     * Returns index of searchable fields
     * @return array
     */
    public function getSearchFieldsIndex(): array;

    /**
     * Return orders for a merchant based on search term
     * @param string $searchTerm
     * @param int $merchantId
     * @return Collection|null
     */
    public function getOrdersBySearchTermAndMerchant(string $searchTerm, int $merchantId): ?Collection;

    /**
     * Gets backend metrics for merchant
     * @param int $merchantId
     * @param string $timePeriod
     * @return array
     */
    public function getDashboardStatisticsForMerchantWithDateRange(int $merchantId, string $timePeriod): array;

    /**
     * Returns formatted metrics for frontend display for merchant
     * @param int $merchantId
     * @param string $timePeriod
     * @return array
     */
    public function getFrontendStatisticsForMerchantWithDateRange(int $merchantId, string $timePeriod): array;

    /**
     * Return all statuses
     * @return array
     */
    public function getAllStatuses(): array;

    /**
     * Returns only the front end status keys from the model map
     * @return array
     */
    public function getFrontendStatusesOnly(): array;
}
