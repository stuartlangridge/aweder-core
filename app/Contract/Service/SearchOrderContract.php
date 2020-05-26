<?php

namespace App\Contract\Service;

use Illuminate\Support\Collection;

interface SearchOrderContract
{
    /**
     * Service wrapper for model repository to search for orders by a string
     * @param string $searchParameter
     * @param int $merchantId
     * @return Collection
     */
    public function searchOrdersByStringAndMerchant(string $searchParameter, int $merchantId): ?Collection;
}
