<?php

namespace Tests\Unit\Repository;

use App\Category;
use App\Contract\Repositories\CategoryContract;
use App\Inventory;
use App\Merchant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

/**
 * Class CategoriesRepository
 * @package Tests\Unit\Repository
 * @group Categories
 */
class CategoriesRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var CategoryContract
     */
    protected CategoryContract $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = app()->make(CategoryContract::class);
    }

    /**
     * @test
     */
    public function create_categories_with_valid_data(): void
    {
        $merchant_id = 123;

        factory(Merchant::class)->create([
            'id' => $merchant_id
        ]);

        $categories = [
            'breakfast',
            'lunch',
            'dinner',
            'dessert'
        ];

        $response = $this->repository->createCategories($categories, $merchant_id);

        $this->assertInstanceOf(Collection::class, $response);

        foreach ($response as $category) {
            $this->assertDatabaseHas('categories', [
                'merchant_id' => $merchant_id,
                'category_id' => $category->category_id,
                'title' => $category->title
            ]);
        }
    }
    /**
     * @test
     */
    public function create_categories_with_some_null_fields(): void
    {
        $merchant_id = 123;

        factory(Merchant::class)->create([
            'id' => $merchant_id
        ]);

        $categories = [
            'breakfast',
            null,
            'dinner',
            null
        ];
        $response = $this->repository->createCategories($categories, $merchant_id);

        $this->assertInstanceOf(Collection::class, $response);

        foreach ($response as $category) {
            $this->assertDatabaseHas('categories', [
                'merchant_id' => $merchant_id,
                'category_id' => $category->category_id,
                'title' => $category->title
            ]);
        }
    }
    /**
     * @test
     */
    public function create_empty_categories(): void
    {
        $merchant_id = 123;

        factory(Merchant::class)->create([
            'id' => $merchant_id
        ]);

        $response = $this->repository->createEmptyCategories($merchant_id);

        $this->assertInstanceOf(Collection::class, $response);

        foreach ($response as $category) {
            $this->assertDatabaseHas('categories', [
                'merchant_id' => $merchant_id,
                'category_id' => $category->category_id,
                'title' => ''
            ]);
        }
    }
    /**
     * @test
     */
    public function get_categories_and_inventory_with_multiple_categories_on_merchant(): void
    {
        $merchant_id = 123;

        factory(Merchant::class)->create([
            'id' => $merchant_id
        ]);

        for ($i = 0; $i < 5; $i++) {
            $category = factory(Category::class)->create([
                'merchant_id' => $merchant_id
            ]);
            factory(Inventory::class)->create([
                'merchant_id' => $merchant_id,
                'category_id' => $category->id
            ]);
        }

        $collection = $this->repository->getCategoryAndInventoryListForUser($merchant_id);

        $this->assertInstanceOf(Collection::class, $collection);

        $arrayOfCategories = $collection->all();

        $this->assertCount(5, $arrayOfCategories);

        foreach ($arrayOfCategories as $category) {
            $this->assertInstanceOf(Collection::class, $category->inventories);
            foreach ($category->inventories as $inventory) {
                $this->assertInstanceOf(Inventory::class, $inventory);
            }
        }
    }
    /**
     * @test
     */
    public function get_categories_and_inventory_with_multiple_categories_on_merchant_with_no_inventory(): void
    {
        $merchant_id = 123;

        factory(Merchant::class)->create([
            'id' => $merchant_id
        ]);

        factory(Category::class)->times(5)->create([
            'merchant_id' => $merchant_id
        ]);

        $collection = $this->repository->getCategoryAndInventoryListForUser($merchant_id);

        $this->assertInstanceOf(Collection::class, $collection);

        $arrayOfCategories = $collection->all();

        $this->assertCount(5, $arrayOfCategories);

        foreach ($arrayOfCategories as $category) {
            $this->assertInstanceOf(Collection::class, $category->inventories);
            $this->assertTrue($category->inventories->isEmpty());
        }
    }
    /**
     * @test
     */
    public function update_categories_with_valid_data(): void
    {
        $merchant_id = 123;

        factory(Merchant::class)->create([
            'id' => $merchant_id
        ]);

        for ($i = 1; $i < 4; $i++) {
            factory(Category::class)->create([
                'merchant_id' => $merchant_id,
                'id' => $i
            ]);
        }

        $categories = [
            1 => 'breakfast',
            2 => 'lunch',
            3 => 'dinner'
        ];

        foreach ($categories as $category) {
            $this->assertDatabaseMissing('categories', [
                'title' => $category
            ]);
        }

        $this->assertTrue($this->repository->updateCategories($categories, $merchant_id));

        foreach ($categories as $category) {
            $this->assertDatabaseHas('categories', [
                'title' => $category
            ]);
        }
    }
    /**
     * @test
     */
    public function update_categories_with_invalid_keys(): void
    {
        $merchant_id = 123;

        factory(Merchant::class)->create([
            'id' => $merchant_id
        ]);

        for ($i = 1; $i < 4; $i++) {
            factory(Category::class)->create([
                'merchant_id' => $merchant_id,
                'id' => $i
            ]);
        }

        $categories = [
            'test' => 'breakfast',
            'test2' => 'lunch',
            'test3' => 'dinner'
        ];

        foreach ($categories as $category) {
            $this->assertDatabaseMissing('categories', [
                'title' => $category
            ]);
        }

        $this->assertTrue($this->repository->updateCategories($categories, $merchant_id));

        foreach ($categories as $category) {
            $this->assertDatabaseMissing('categories', [
                'title' => $category
            ]);
        }
    }
}
