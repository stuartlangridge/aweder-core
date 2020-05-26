<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Psr\Log\LoggerInterface;
use App\Contract\Repositories\CategoryContract;
use App\Category;

/**
 * Class CategoryRepository
 * @package App\Repository
 */
class CategoryRepository implements CategoryContract
{
    protected Category $model;

    protected LoggerInterface $logger;

    /**
     * CategoryRepository constructor.
     * @param \App\Category $model
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(Category $model, LoggerInterface $logger)
    {
        $this->model = $model;

        $this->logger = $logger;
    }

    public function createEmptyCategories(int $merchantId): SupportCollection
    {
        $newCategories = [
            '',
            '',
            '',
            '',
        ];

        return $this->createCategories($newCategories, $merchantId);
    }

    /**
     * @param int $merchantId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCategoryAndInventoryListForUser(int $merchantId): Collection
    {
        return $this->getModel()
            ->query()
            ->where('merchant_id', '=', $merchantId)
            ->with('inventories')
            ->get();
    }

    public function createCategories(array $categories, int $merchant_id): SupportCollection
    {
        $categories = array_values($categories);

        $categoryCollection = new SupportCollection();

        foreach ($categories as $key => $value) {
            $category = $this->getModel()->create([
                'merchant_id' => $merchant_id,
                'category_id' => $key + 1,
                'title' => $value
            ]);
            $categoryCollection->add($category);
        }

        return $categoryCollection;
    }

    public function updateCategories(array $categories, int $merchantId): bool
    {
        foreach ($categories as $key => $category) {
            $categoryModel = $this->getModel()->where('id', $key)->first();

            if ($categoryModel instanceof Category) {
                $categoryModel->update([
                    'title' => $category
                ]);
            }
        }

        return true;
    }

    /**
     * @return Category
     */
    protected function getModel(): Category
    {
        return $this->model;
    }
}
