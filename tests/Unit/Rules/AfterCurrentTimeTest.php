<?php

namespace Tests\Unit\Rules;

use App\Rules\AfterCurrentTime;
use Carbon\Carbon;
use Tests\TestCase;

/**
 * Class AfterCurrentTime
 * @package Tests\Unit\Rules
 * @group Rules
 */
class AfterCurrentTimeTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function after_current_time_with_time_past_current_time(): void
    {
        $time = Carbon::now()->addMinutes(50);
        $rule = new AfterCurrentTime($time);
        $this->assertTrue($rule->passes('test', 'test'));
    }
    /**
     * @test
     */
    public function after_current_time_with_time_before_current_time(): void
    {
        $time = Carbon::now()->subMinutes(50);
        $rule = new AfterCurrentTime($time);
        $this->assertFalse($rule->passes('test', 'test'));
    }
}
