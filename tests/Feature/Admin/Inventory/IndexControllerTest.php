<?php

namespace Tests\Feature\Admin\Inventory;

use App\Merchant;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class SetupControllerTest
 * @package Tests\Feature\Home
 * @coversDefaultClass \App\Http\Controllers\Admin\Inventory\SetupController
 * @group AdminInventory
 */
class IndexControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function willRedirectIfNotAuthorised(): void
    {
        $response = $this->get('/admin/inventory');
        $response->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function willReturnViewIfAuthorised(): void
    {
        $user = factory(User::class)->create();

        $merchant = factory(Merchant::class)->create();

        $user->merchants()->attach($merchant->id);

        $this->actingAs($user);
        $response = $this->get('/admin/inventory');

        $response->assertOk();
    }
}
