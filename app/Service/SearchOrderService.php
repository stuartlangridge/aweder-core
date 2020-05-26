<?php

namespace App\Service;

use App\Contract\Repositories\OrderContract;
use App\Contract\Service\SearchOrderContract;
use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;

class SearchOrderService implements SearchOrderContract
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var OrderContract
     */
    protected OrderContract $orderRepository;

    public function __construct(LoggerInterface $logger, OrderContract $orderRepository)
    {
        $this->logger = $logger;
        $this->orderRepository = $orderRepository;
    }

    public function searchOrdersByStringAndMerchant(string $searchParameter, int $merchantId): ?Collection
    {
        return $this->orderRepository->getOrdersBySearchTermAndMerchant($searchParameter, $merchantId);
    }
}
