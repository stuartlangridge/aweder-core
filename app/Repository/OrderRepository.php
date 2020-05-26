<?php

namespace App\Repository;

use App\Contract\Repositories\OrderContract;
use App\Traits\HelperTrait;
use App\Inventory;
use App\Order;
use App\OrderItem;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderContract
{
    use HelperTrait;

    /**
     * @var Order
     */
    protected $model;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(Order $model, LoggerInterface $logger)
    {
        $this->model = $model;

        $this->logger = $logger;
    }

    public function getUnprocessedOrdersBetweenPeriod(Carbon $start, Carbon $end): Collection
    {
        return $this->getModel()
            ->with('merchant')
            ->whereIn('status', ['purchased', 'processing'])
            ->whereBetween('order_submitted', [$start, $end])
            ->orderBy('order_submitted')
            ->get();
    }

    public function getUnprocessedOrdersBetweenPeriodWhereNoRemindersHaveBeenSentForTime(
        Carbon $start,
        Carbon $end,
        int $minutes
    ): Collection {
        return $this->getModel()
            ->with('merchant')
            ->whereIn('status', ['purchased', 'processing'])
            ->whereDoesntHave('reminders', function (Builder $query) use ($minutes) {
                $query->where('reminder_time', $minutes);
            })
            ->whereBetween('order_submitted', [$start, $end])
            ->orderBy('order_submitted')
            ->get();
    }

    public function retrieveOrderForMerchantByOrderNo(int $merchantId, string $orderNo): Order
    {
        try {
            return $this->getModel()
                ->where('merchant_id', $merchantId)
                ->where('url_slug', $orderNo)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $this->logger->error(
                'OrderRepository::retrieveOrderForMerchantByOrderNo ' . $e->getMessage()
                . ' merchant ' . $merchantId . ' order ' . $orderNo
            );

            return $this->createEmptyOrderWithStatus($merchantId, 'incomplete');
        }
    }

    public function createEmptyOrderWithStatus(int $merchantId, string $status): Order
    {
        $order = $this->getModel()
            ->create(
                [
                    'merchant_id' => $merchantId,
                    'status' => $status,
                    'url_slug' => $this->generateSlugUrl(8),
                ]
            );

        $order->save();

        return $order;
    }

    public function setOrderToUnacknowledged(Order $order): bool
    {
        return $order->update(['status' => 'unacknowledged']);
    }

    public function setOrderToAcknowledged(Order $order): bool
    {
        return $order->update(['status' => 'acknowledged']);
    }

    public function updateAvailableTimeOnOrder(Order $order, string $time): bool
    {
        return $order->update(['available_time' => $time]);
    }

    public function getBackendStatusesFromFrontendSearchStatus(string $status): array
    {
        return $this->model->getBackendStatusesFromFrontendSearchStatus($status);
    }

    public function updateQuantityOnItemInOrder(Order $order, int $itemId): bool
    {
        $item = $order->items()->where('inventory_id', $itemId)->first();

        $item->increment('quantity');

        return true;
    }

    public function addItemToOder(Order $order, Inventory $inventoryItem, int $quantity): bool
    {
        $orderItem = new OrderItem(
            [
                'inventory_id' => $inventoryItem->id,
                'quantity' => 1,
                'price' => $inventoryItem->price,
            ]
        );

        $order->items()->save($orderItem);

        return true;
    }

    public function removeItemFromOrder(Order $order, int $itemId): bool
    {
        return $order->items()->where('inventory_id', '=', $itemId)->delete();
    }

    public function hasOrderBeenPreviouslySubmitted(Order $order): bool
    {
        $submittedStatuses = $this->getModel()->getSubmittedStatuses();

        return in_array($order->status, $submittedStatuses);
    }

    public function hasOrderGonePastStage(Order $order, string $newStatusToChangeTo): bool
    {
        $afterStatus = [];

        switch ($newStatusToChangeTo) {
            case 'processing':
                $afterStatus = $this->getModel()->getProcessingStatuses();
                break;
            case 'rejected':
                $afterStatus = $this->getModel()->getRejectedStatuses();
                break;
            case 'accepted':
                $afterStatus = $this->getModel()->getAcceptedStatuses();
                break;
            case 'acknowledged':
                $afterStatus = $this->getModel()->getAcknowledgedStatuses();
                break;
            case 'fulfilled':
                $afterStatus = $this->getModel()->getFulfilledStatuses();
                break;
        }

        return in_array($order->status, $afterStatus);
    }

    public function updateOrderStatus(Order $order, string $orderStatus): bool
    {
        $order->status = $orderStatus;

        return $order->save();
    }

    public function attachNoteToOrder(Order $order, string $noteType, string $note): bool
    {
        $order->{$noteType} = $note;

        return $order->save();
    }

    public function getOrdersByMerchantAndStatus(int $merchantId, string $status, string $returnOrder): Collection
    {
        return $this->getModel()->where('merchant_id', $merchantId)
            ->status($status)
            ->orderBy('created_at', $returnOrder)
            ->get();
    }

    public function getOrdersByMerchantAndStatuses(
        int $merchantId,
        array $statuses,
        string $returnOrder,
        string $startDate,
        string $endDate
    ): Collection {
        return $this->getModel()->where('merchant_id', $merchantId)
            ->whereIn('status', $statuses)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', $returnOrder)
            ->get();
    }

    public function getOrdersByMerchantAndStatsPaginated(
        int $merchantId,
        string $status,
        string $returnOrder,
        int $limit = 15,
        string $pageName = 'page'
    ): LengthAwarePaginator {
        return $this->getModel()->where('merchant_id', $merchantId)
            ->status($status)
            ->orderBy('created_at', $returnOrder)
            ->paginate($limit, ['*'], $pageName);
    }

    public function storeCustomerDetailsOnOrder(Order $order, array $customerDetails = []): bool
    {
        return $order->update($customerDetails);
    }

    public function getOrderByOrderNo(string $orderNo): ?Order
    {
        try {
            return $this->getModel()->with('merchant')->where('url_slug', $orderNo)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $this->logger->error('OrderRepository::getOrderByOrderNo ' . $e->getMessage());
            return null;
        }
    }

    public function getOrdersBySearchTermAndMerchant(string $searchTerm, int $merchantId): ?Collection
    {
        $merchantRecords = $this->getModel()->where('merchant_id', $merchantId);
        $searchableFieldsIndex = $this->getSearchFieldsIndex();

        $merchantRecords->where(function (Builder $query) use ($searchableFieldsIndex, $searchTerm) {
            foreach ($searchableFieldsIndex as $searchField) {
                $query->orWhere($searchField, 'like', '%' . strtolower($searchTerm) . '%');
            }
        });

        return $merchantRecords->get();
    }

    protected function getModel(): Order
    {
        return $this->model;
    }

    public function getFormattedStatuses(): array
    {
        return $this->getModel()->getFrontendFilterFormattedStatuses();
    }

    public function getAllStatuses(): array
    {
        return $this->getModel()->getAllStatuses();
    }

    public function getFrontendStatusesOnly(): array
    {
        return $this->getModel()->getAllFrontendStatuses();
    }

    public function getSearchFieldsIndex(): array
    {
        return [
            'customer_email',
            'customer_name',
            'customer_phone',
            'url_slug'
        ];
    }

    public function getDashboardStatisticsForMerchantWithDateRange($merchantId, string $timePeriod): array
    {
        [$startDate, $endDate] = $this->getTimePeriodForOrders($timePeriod);

        $query = $this->getModel()
            ->select('status', DB::raw('count(*) count'))
            ->where('merchant_id', $merchantId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('status')
            ->get();

        $returnArray = [];
        foreach ($query as $metric) {
            $returnArray[$metric['status']] = $metric['count'];
        }

        return $returnArray;
    }

    public function getFrontendStatisticsForMerchantWithDateRange(int $merchantId, string $timePeriod): array
    {
        $unformattedMetrics = collect($this->getDashboardStatisticsForMerchantWithDateRange($merchantId, $timePeriod));
        $formattedMetrics = [];
        foreach ($this->getModel()->getFrontendStatusMap() as $frontEndKey => $backendKeysArray) {
            $keysToSum = $unformattedMetrics->only($backendKeysArray);
            $formattedMetrics[$frontEndKey] = $keysToSum->sum();
        }
        return $formattedMetrics;
    }
}
