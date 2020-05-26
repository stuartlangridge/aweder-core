<?php

namespace Tests\Feature\Home;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class IndexControllerTest
 * @package Tests\Feature\Home
 * @coversDefaultClass \App\Http\Controllers\Home\IndexController
 * @group Homepage
 */
class SetupControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     * @test
     *
     * @return void
     */
    public function homepageLoads(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
