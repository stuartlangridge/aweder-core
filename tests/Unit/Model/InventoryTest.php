<?php

namespace Tests\Unit\Model;

use App\Inventory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class InventoryTest
 * @package Tests\Unit\Model
 * @group Inventory
 * @coversDefaultClass \App\Inventory
 */
class InventoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
    }
}
