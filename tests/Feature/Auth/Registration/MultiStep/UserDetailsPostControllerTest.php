<?php

namespace Tests\Feature\Auth\Registration\MultiStep;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Class UserDetailsPostControllerTest
 * @package Tests\Feature\Auth\Registration\MultiStep
 * @coversDefaultClass \App\Http\Controllers\Auth\Registration\MultiStep\UserDetails\UserDetailsPost
 * @group MultiStageRegistration
 */
class UserDetailsPostControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     *
     */
    public function userCantRegisterWithOutPassword()
    {
        $userPostDetails = [
            'email' => $this->faker->safeEmail
        ];

        $response = $this->from(route('register'))
            ->post(route('register.user-details.post'), $userPostDetails);

        $response->assertRedirect(route('register'));

        $response->assertSessionHasErrors('password');
    }

    /**
     * @test
     *
     */
    public function userCantRegisterWithMatchingPassword()
    {
        $userPostDetails = [
            'email' => $this->faker->safeEmail,
            'password' => '123kjh12312',
            'password-confirmed' => '123'
        ];

        $response = $this->from(route('register'))
            ->post(route('register.user-details.post'), $userPostDetails);

        $response->assertRedirect(route('register'));

        $response->assertSessionHasErrors('password-confirmed');
    }

    /**
     * @test
     *
     */
    public function userCantRegisterWithWeakPassword()
    {
        $userPostDetails = [
            'email' => $this->faker->safeEmail,
            'password' => '123',
            'password-confirmed' => '123',
        ];

        $response = $this->from(route('register'))
            ->post(route('register.user-details.post'), $userPostDetails);

        $response->assertRedirect(route('register'));

        $response->assertSessionHasErrors('password');
    }

    /**
     * @test
     *
     */
    public function userCanRegisterWithStrongPassword()
    {
        Mail::fake();

        $password = $this->faker->password(12);

        $userPostDetails = [
            'email' => $this->faker->safeEmail,
            'password' => $password,
            'password-confirmed' => $password,
        ];

        $response = $this->from(route('register'))
            ->post(route('register.user-details.post'), $userPostDetails);

        $response->assertRedirect(route('register.business-details'));

        $users = User::all();

        $this->assertCount(1, $users);
        $this->assertAuthenticatedAs($user = $users->first());
    }
}
