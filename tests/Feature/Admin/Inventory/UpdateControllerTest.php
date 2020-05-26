<?php

namespace Tests\Feature\Admin\Inventory;

use App\Contract\Repositories\InventoryContract;
use App\Http\Controllers\Admin\Inventory\UpdateController;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UpdateControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var InventoryContract
     */
    protected InventoryContract $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = app()->make(InventoryContract::class);
    }

    /**
     * @test
     */
    public function willUpdateIfLoggedInWithPayload(): void
    {
        $user = factory(User::class)->create();

        $originalTitle = 'Average Omlette';
        $originalDescription = 'Uses 2 eggs not 3';
        $newTitle = 'Pretty good Omlette';

        $inventoryItem = $this->createAndReturnInventoryItem(
            [
                'title' => $originalTitle,
                'description' => $originalDescription
            ]
        );

        $this->assertDatabaseHas(
            'inventories',
            [
                'id' => $inventoryItem->id,
                'title' => $originalTitle,
            ]
        );

        $this->actingAs($user);
        $response = $this->put(route('admin.inventory.update', $inventoryItem->id), [
            'title' => $newTitle,
            'price' => 10
        ]);
        $response->assertRedirect(route('admin.inventory'));

        $this->assertDatabaseHas(
            'inventories',
            [
                'id' => $inventoryItem->id,
                'title' => $newTitle,
            ]
        );
    }

    /**
     * @test
     */
    public function cannotUpdateInventoryIfNotLoggedIn(): void
    {
        $inventoryItem = $this->createAndReturnInventoryItem(
            [
                'title' => 'An item that will not be updated',
                'description' => 'Some description'
            ]
        );

        $this->assertDatabaseHas(
            'inventories',
            [
                'id' => $inventoryItem->id,
                'title' => 'An item that will not be updated',
            ]
        );

        $response = $this->put(route('admin.inventory.update', $inventoryItem->id), [
            'title' => 'Nachos and Chips',
            'price' => 10
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function willUploadImageToStorage(): void
    {
        Storage::fake('s3');

        $user = factory(User::class)->create();

        $inventoryItem = $this->createAndReturnInventoryItem(
            [
                'title' => 'Item with Image',
                'description' => 'an item containing an image',
                'image' => null
            ]
        );

        $this->assertDatabaseHas(
            'inventories',
            [
                'id' => $inventoryItem->id,
                'title' => 'Item with Image',
                'image' => null
            ]
        );

        $uploadedFile = UploadedFile::fake()->image('testimage.png', 100, 100)->size(100);

        $this->actingAs($user);
        $response = $this->put(route('admin.inventory.update', $inventoryItem->id), [
            'title' => $inventoryItem->title,
            'price' => $inventoryItem->price,
            'image' => $uploadedFile
        ]);

        $this->assertDatabaseMissing(
            'inventories',
            [
                'id' => $inventoryItem->id,
                'title' => 'Item with Image',
                'image' => null,
            ]
        );

        $response->assertRedirect(route('admin.inventory'));
    }
}
