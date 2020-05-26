<?php

namespace Tests\Unit\Traits;

use App\Traits\HelperTrait;
use Carbon\Carbon;
use Tests\TestCase;

/**
 * Class HelperTraitTest
 * @group Trait
 * @group HelperTrait
 * @package Tests\Unit\Traits
 */
class HelperTraitTest extends TestCase
{
    use HelperTrait;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     *
     * @param $date
     * @param $expectedFormattedDate
     *
     * @dataProvider DateProvider
     */
    public function get_formatted_date_for_email($date, $expectedFormattedDate): void
    {
        $formatted_value = $this->getFormattedDateForEmail($date);

        $this->assertEquals($expectedFormattedDate, $formatted_value);
    }

    /**
     * @test
     *
     * @dataProvider priceProvider
     *
     * @param int $value
     * @param string $expected_value
     */
    public function format_values_without_delivery_cost(int $value, string $expected_value): void
    {
        $this->assertEquals($expected_value, $this->getFormattedUKPriceAttribute($value));
    }

    /**
     * @test
     *
     * @dataProvider priceAndDeliveryProvider
     *
     * @param int $value
     * @param int $delvery
     * @param string $expected_value
     */
    public function format_values_with_delivery_cost(int $value, int $delvery, string $expected_value): void
    {
        $this->assertEquals($expected_value, $this->getFormattedUKPriceAttribute($value, $delvery));
    }


    public function dateProvider(): array
    {
        return [
            [
                Carbon::now()->addDays(3)->hour(17)->minute(30),
                Carbon::now()->addDays(3)->hour(17)->minute(30)->format('g:i a'),
            ],
            [
                Carbon::now()->addDays(3)->hour(8)->minute(30),
                Carbon::now()->addDays(3)->hour(8)->minute(30)->format('g:i a'),
            ],
        ];
    }

    public function priceProvider(): array
    {
        return [
            [2000, '20.00'],
            [5000, '50.00'],
            [6070, '60.70'],
            [500, '5.00']
        ];
    }

    public function priceAndDeliveryProvider(): array
    {
        return [
            [2000, 1000, '30.00'],
            [5000, 500, '55.00'],
            [6070, 1500, '75.70'],
            [500, 100, '6.00']
        ];
    }
}
