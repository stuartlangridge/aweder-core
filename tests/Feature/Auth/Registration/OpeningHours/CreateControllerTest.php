<?php

namespace Tests\Feature\Auth\Registration\OpeningHours;

use App\Merchant;
use App\User;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Class CreateControllerTest
 * @coversDefaultClass \App\Http\Controllers\Admin\OpeningHours\CreateController
 * @group OpeningHours
 */
class CreateControllerTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function redirects_when_not_admin(): void
    {
        $this->post('registration/opening-hours')
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

        $this->from(route('registration.opening-hours'))
            ->post(route('registration.opening-hours.post'), $days)
            ->assertRedirect(route('registration.categories'))
            ->assertStatus(Response::HTTP_FOUND);

        $days = array_values($days);

        foreach ($days as $key => $day) {
            $this->assertDatabaseHas('normal_opening_hours', [
                'merchant_id' => $merchant->id,
                'day_of_week' => $key + 1,
                'open_time' => $day['opening']['hour'] . ':' . $day['opening']['minute'],
                'close_time' => $day['closing']['hour'] . ':' .  $day['closing']['minute'],
            ]);
        }
    }
}
