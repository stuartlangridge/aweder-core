<?php

namespace Tests\Unit\Repository;

use App\Contract\Repositories\NormalOpeningHoursContract;
use App\Merchant;
use App\NormalOpeningHour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class NormalOpeningHoursRepository
 * @package Tests\Unit\Repository
 * @group OpeningHours
 */
class NormalOpeningHoursRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var NormalOpeningHoursContract
     */
    protected $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = app()->make(NormalOpeningHoursContract::class);
    }

    /**
     * @test
     */
    public function create_normal_opening_hours_with_valid_data(): void
    {
        $merchant_id = 123;

        factory(Merchant::class)->create([
            'id' => $merchant_id
        ]);

        $days = [
            'monday' => [
                'opening' => [
                    'hour' => '08',
                    'minute' => '00',
                ],
                'closing' => [
                    'hour' => '17',
                    'minute' => '00',
                ],
            ],
            'tuesday' => [
                'opening' => [
                    'hour' => '08',
                    'minute' => '00',
                ],
                'closing' => [
                    'hour' => '17',
                    'minute' => '00',
                ],
            ],
            'wednesday' => [
                'opening' => [
                    'hour' => '08',
                    'minute' => '00',
                ],
                'closing' => [
                    'hour' => '17',
                    'minute' => '00',
                ],
            ],
            'thursday' => [
                'opening' => [
                    'hour' => '08',
                    'minute' => '00',
                ],
                'closing' => [
                    'hour' => '17',
                    'minute' => '00',
                ],
            ],
            'friday' => [
                'opening' => [
                    'hour' => '08',
                    'minute' => '00',
                ],
                'closing' => [
                    'hour' => '17',
                    'minute' => '00',
                ],
            ],
            'saturday' => [
                'opening' => [
                    'hour' => '08',
                    'minute' => '00',
                ],
                'closing' => [
                    'hour' => '17',
                    'minute' => '00',
                ],
            ],
            'sunday' => [
                'opening' => [
                    'hour' => '08',
                    'minute' => '00',
                ],
                'closing' => [
                    'hour' => '17',
                    'minute' => '00',
                ],
            ]
        ];
        $response = $this->repository->createNormalOpeningHours($days, $merchant_id);

        $this->assertInstanceOf(NormalOpeningHour::class, $response);

        $days = array_values($days);

        foreach ($days as $key => $day) {
            $this->assertDatabaseHas('normal_opening_hours', [
                'merchant_id' => $merchant_id,
                'day_of_week' => $key + 1,
                'open_time' => $day['opening']['hour'] . ':' . $day['opening']['minute'] ,
                'close_time' => $day['closing']['hour'] . ':' .  $day['closing']['minute'],
            ]);
        }
    }

    /**
     * @test
     * @dataProvider dayNameDataProvider
     */
    public function convert_day_name_to_integer_with_valid_data($name, $expected)
    {
        $response = $this->repository->convertDayNameToInteger($name);

        $this->assertEquals($expected, $response);
    }

    /**
     * @test
     */
    public function convert_day_name_to_integer_with_invalid_day()
    {
        $response = $this->repository->convertDayNameToInteger('test');

        $this->assertNull($response);
    }

    public function dayNameDataProvider(): array
    {
        return [
            ['monday', 1],
            ['tuesday', 2],
            ['wednesday', 3],
            ['thursday', 4],
            ['friday', 5],
            ['saturday', 6],
            ['sunday', 7]
        ];
    }
}
