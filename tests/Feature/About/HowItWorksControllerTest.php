<?php

namespace Tests\Feature\About;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class AboutHowItWorksControllerTest
 * @package Tests\Feature
 * @coversDefaultClass \App\Http\Controllers\About\HowItWorksController
 */
class HowItWorksControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     * @test
     *
     * @return void
     */
    public function pageLoads()
    {
        $response = $this->get('/how-it-works');

        $response->assertStatus(200);
    }
}
