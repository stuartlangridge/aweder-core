<?php

namespace App\Contract\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

/**
 * Interface CategoryContract
 * @package App\Contract\Repositories
 */
interface CategoryContract
{
    /**
     * @param int $merchantId
     * @return SupportCollection
     */
    public function createEmptyCategories(int $merchantId): SupportCollection;

    /**
     * Creates categories in the db
     *
     * @param array $categories
     * @param int $merchantId
     *
     * @return mixed
     */
    public function createCategories(array $categories, int $merchantId): SupportCollection;

    /**
     * @param array $categories
     * @param int $merchantId
     * @return bool
     */
    public function updateCategories(array $categories, int $merchantId): bool;

    /**
     * @param int $merchantId
     * @return Collection
     */
    public function getCategoryAndInventoryListForUser(int $merchantId): Collection;
}
