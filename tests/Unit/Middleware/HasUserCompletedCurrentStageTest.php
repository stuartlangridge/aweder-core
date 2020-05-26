<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\HasUserCompletedCurrentStage;
use App\Merchant;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Class HasUserCompletedCurrentStageTest
 * @package Tests\Unit\Middleware
 * @coversDefaultClass \App\Http\Middleware\HasUserCompletedCurrentStage
 * @group Middleware
 */
class HasUserCompletedCurrentStageTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     */
    public function willPassThroughMiddlewareWhenFullySignedUpAndAccessNonRegistrationRoute()
    {
        $user = factory(User::class)->create();

        $merchant = factory(Merchant::class)->create(['registration_stage' => 0]);

        $user->merchants()->attach($merchant->id);

        $this->actingAs($user);

        $request = Request::create(route('admin.dashboard'));

        $authManager = $this->app->make(\Illuminate\Contracts\Auth\Factory::class);

        $middleware = new HasUserCompletedCurrentStage($authManager);

        $response = $middleware->handle(
            $request,
            function () {
            },
            0
        );

        $this->assertNull($response);
    }

    /**
     * @dataProvider registrationRoutesProvider
     * @test
     */
    public function willPreventAccessToRegistrationWhenFullySignedUp(string $route)
    {
        $user = factory(User::class)->create();

        $merchant = factory(Merchant::class)->create(['registration_stage' => 0]);

        $user->merchants()->attach($merchant->id);

        $response = $this->actingAs($user)->get($route);

        $response->assertStatus(Response::HTTP_FOUND);

        $response->assertRedirect('/admin/dashboard');
    }

    public function registrationRoutesProvider(): array
    {
        return [
            'cannot access register business details' => ['register/business-details'],
            'cannot access register contact details' => ['register/contact-details'],
            'cannot access register business address' => ['register/business-address'],
        ];
    }

    /**
     * @dataProvider validStageProvider
     * @test
     */
    public function willNotRedirectUserWhenItIsTheirCurrentStage(array $providedData)
    {
        $user = factory(User::class)->create();

        $merchant = factory(Merchant::class)->create(
            [
                'registration_stage' => $providedData['registration_stage']
            ]
        );

        $user->merchants()->attach($merchant->id);

        $response = $this->actingAs($user)->get($providedData['route']);

        $response->assertSessionDoesntHaveErrors();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function validStageProvider(): array
    {
        return [
            'will allow user access to contact details when they are on that stage' => [
                [
                    'registration_stage' => 3,
                    'route' => 'register/contact-details'
                ]
            ],
            'will allow user access to add business address when they are on that stage' => [
                [
                    'registration_stage' => 4,
                    'route' => 'register/business-address'
                ]
            ]
        ];
    }

    /**
     * @dataProvider invalidStageProvider
     * @test
     */
    public function willCorrectlyPreventUserAccessingStageTheyAreNotOn(array $providedData)
    {
        $user = factory(User::class)->create();

        $merchant = factory(Merchant::class)->create(['registration_stage' => $providedData['stage']]);

        $user->merchants()->attach($merchant->id);

        $response = $this->actingAs($user)->get($providedData['route']);

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());

        $response->assertSessionHas(['error' => $providedData['response']]);

        $response->assertRedirect($providedData['redirect']);
    }

    public function invalidStageProvider(): array
    {
        return [
            'user has not completed business details' => [
                [
                    'stage' => 1,
                    'route' => '/admin/dashboard',
                    'response' => 'You need to complete your business details',
                    'redirect' => '/register/business-details'
                ]
            ],
            'user has not completed contact details' => [
                [
                    'stage' => 3,
                    'route' => '/admin/dashboard',
                    'response' => 'You need to complete your contact details',
                    'redirect' => '/register/contact-details'
                ]
            ],
            'user has not filled in business address' => [
                [
                    'stage' => 4,
                    'route' => '/admin/dashboard',
                    'response' => 'You need to complete your business address',
                    'redirect' => '/register/business-address'
                ]
            ]
        ];
    }
}
