<?php

namespace Tests\Unit\Rules;

use App\Rules\AtLeastOneFieldRequired;
use Tests\TestCase;

/**
 * Class AtLeastOnefieldRequired
 * @package Tests\Unit\Rules
 * @group Rules
 */
class AtLeastOneFieldRequiredTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function at_least_one_field_required_rule_with_fields_filled()
    {
        $rule = new AtLeastOneFieldRequired();
        $this->assertTrue($rule->passes('test', [
            'test',
            'test_2'
        ]));
    }
    /**
     * @test
     */
    public function at_least_one_field_required_rule_with_empty_fields()
    {
        $rule = new AtLeastOneFieldRequired();
        $this->assertFalse($rule->passes('test', [
            null,
            null
        ]));
    }
}
