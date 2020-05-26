<?php

namespace Tests\Feature\Category;

use App\Merchant;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Class CreateControllerTest
 * @coversDefaultClass \App\Http\Controllers\Admin\Categories\CreateController
 * @group Categories
 */
class CreateControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function redirects_when_not_admin(): void
    {
        $this->post('admin/categories')
            ->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect('/login');
    }

    /**
     * @test
     *
     * @return void
     */
    public function table_is_populated_when_called_with_valid_data(): void
    {
        $user = factory(User::class)->create();

        $merchant = factory(Merchant::class)->create();

        $user->merchants()->attach($merchant->id);

        $this->actingAs($user);

        $categories = [
            'categories' => [
                'breakfast',
                'lunch',
                'dinner',
                'dessert',
            ]
        ];

        $response = $this->post('registration/categories', $categories);

        $response->assertRedirect(route('admin.inventory'));

        foreach ($categories['categories'] as $key => $category) {
            $this->assertDatabaseHas('categories', [
                'merchant_id' => $merchant->id,
                'category_id' => (int) $key + 1,
                'title' => $category
            ]);
        }
    }
    /**
     * @test
     *
     * @return void
     */
    public function table_is_populated_when_called_with_some_null_fields(): void
    {
        $user = factory(User::class)->create();

        $merchant = factory(Merchant::class)->create();

        $user->merchants()->attach($merchant->id);

        $this->actingAs($user);

        $categories = [
            'categories' => [
                'breakfast',
                null,
                'dinner',
                null,
            ]
        ];

        $response = $this->post('registration/categories', $categories);

        $response->assertRedirect(route('admin.inventory'));

        foreach ($categories['categories'] as $key => $category) {
            $this->assertDatabaseHas(
                'categories',
                [
                    'merchant_id' => $merchant->id,
                    'category_id' => (int) $key + 1,
                    'title' => $category
                ]
            );
        }
    }
}
