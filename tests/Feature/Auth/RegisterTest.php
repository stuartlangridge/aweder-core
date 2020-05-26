<?php

namespace Tests\Feature\Auth;

use App\Mail\Aweder\MerchantConfirmationSignUpEmail;
use App\Mail\Merchant\SignUpEmail;
use App\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class RegisterTest
 * @package Tests\Feature\Auth
 * @coversDefaultClass \App\Http\Controllers\Auth\RegisterController
 * @group Registration
 */
class RegisterTest extends TestCase
{
    use RefreshDatabase;

    protected function successfulRegistrationRoute()
    {
        return url()->route('registration.opening-hours');
    }

    protected function registerGetRoute()
    {
        return route('register');
    }

    protected function registerPostRoute()
    {
        return route('register.manage');
    }

    protected function guestMiddlewareRoute()
    {
        return url()->to('/admin/dashboard');
    }

    public function testUserCanViewARegistrationForm()
    {
        $this->markTestSkipped('These tests are no longer required;');
        $response = $this->get($this->registerGetRoute());

        $response->assertSuccessful();
        $response->assertViewIs('auth.register');
    }

    public function testUserCannotViewARegistrationFormWhenAuthenticated()
    {
        $this->markTestSkipped('These tests are no longer required;');
        $user = factory(User::class)->make();

        $response = $this->actingAs($user)->get($this->registerGetRoute());

        $response->assertRedirect($this->guestMiddlewareRoute());
    }

    public function testUserCanRegister()
    {
        $this->markTestSkipped('These tests are no longer required;');
        $userDetails = $this->createCompleteRegistrationDetails();

        $this->assertCount(0, $users = User::all());

        Mail::fake();

        $response = $this->post($this->registerPostRoute(), $userDetails);

        $response->assertRedirect($this->successfulRegistrationRoute());
        $this->assertCount(1, $users = User::all());
        $this->assertAuthenticatedAs($user = $users->first());
        Mail::assertSent(SignUpEmail::class);
        Mail::assertSent(MerchantConfirmationSignUpEmail::class);
    }

    public function testUserCannotRegisterWithoutEmail()
    {
        $this->markTestSkipped('These tests are no longer required;');
        $userDetails = $this->createCompleteRegistrationDetails();
        unset($userDetails['email']);

        $response = $this->from($this->registerGetRoute())
            ->post($this->registerPostRoute(), $userDetails);

        $users = User::all();

        $this->assertCount(0, $users);
        $response->assertRedirect($this->registerGetRoute());
        $response->assertSessionHasErrors('email');
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function testUserCannotRegisterWithInvalidEmail()
    {
        $this->markTestSkipped('These tests are no longer required;');
        $userDetails = $this->createCompleteRegistrationDetails();
        $userDetails['email'] = 'invalid-email';

        $response = $this->from($this->registerGetRoute())->post($this->registerPostRoute(), $userDetails);

        $users = User::all();

        $this->assertCount(0, $users);
        $response->assertRedirect($this->registerGetRoute());
        $response->assertSessionHasErrors('email');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function testUserCannotRegisterWithoutPassword()
    {
        $this->markTestSkipped('These tests are no longer required;');
        $userDetails = $this->createCompleteRegistrationDetails();
        unset($userDetails['password'], $userDetails['password_confirmation']);

        $response = $this->from($this->registerGetRoute())->post($this->registerPostRoute(), $userDetails);

        $users = User::all();

        $this->assertCount(0, $users);
        $response->assertRedirect($this->registerGetRoute());
        $response->assertSessionHasErrors('password');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function testUserCannotRegisterWithoutPasswordConfirmation()
    {
        $this->markTestSkipped('These tests are no longer required;');
        $userDetails = $this->createCompleteRegistrationDetails();
        unset($userDetails['password']);

        $response = $this->from($this->registerGetRoute())->post($this->registerPostRoute(), $userDetails);

        $users = User::all();

        $this->assertCount(0, $users);
        $response->assertRedirect($this->registerGetRoute());
        $response->assertSessionHasErrors('password');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function testUserCannotRegisterWithPasswordsNotMatching()
    {
        $this->markTestSkipped('These tests are no longer required;');
        $userDetails = $this->createCompleteRegistrationDetails();

        $userDetails['password'] = '123123';

        $userDetails['password-confirmed'] = '12315346';

        $response = $this->from($this->registerGetRoute())
            ->post(
                $this->registerPostRoute(),
                $userDetails
            );

        $users = User::all();

        $this->assertCount(0, $users);

        $response->assertRedirect($this->registerGetRoute());
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /**
     * @test
     * @group Delivery
     */
    public function merchantWithDeliveryOptionSelectedCantSubmitWithoutDeliveryCostDetailsPresent()
    {
        $this->markTestSkipped('These tests are no longer required;');
        $userDetails = $this->createCompleteRegistrationDetails();

        $userDetails['collection_type'] = 'delivery';
        unset($userDetails['delivery_cost'], $userDetails['delivery_radius']);

        $response = $this->from($this->registerGetRoute())
            ->post(
                $this->registerPostRoute(),
                $userDetails
            );

        $users = User::all();

        $this->assertCount(0, $users);

        $response->assertRedirect($this->registerGetRoute());
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $response->assertSessionHasErrors('delivery_cost');
        $response->assertSessionHasErrors('delivery_radius');

        $this->assertGuest();
    }

    /**
     * @test
     * @group Delivery
     */
    public function merchantWithBothOptionSelectedCantSubmitWithoutDeliveryCostDetailsPresent()
    {
        $this->markTestSkipped('These tests are no longer required;');
        $userDetails = $this->createCompleteRegistrationDetails();

        $userDetails['collection_type'] = 'both';
        unset($userDetails['delivery_cost'], $userDetails['delivery_radius']);

        $response = $this->from($this->registerGetRoute())
            ->post(
                $this->registerPostRoute(),
                $userDetails
            );

        $users = User::all();

        $this->assertCount(0, $users);

        $response->assertRedirect($this->registerGetRoute());
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $response->assertSessionHasErrors('delivery_cost');
        $response->assertSessionHasErrors('delivery_radius');

        $this->assertGuest();
    }

    /**
     * @test
     * @group Delivery
     */
    public function merchantWithCollectionAndNoErrorWithoutDeliveryFields()
    {
        $this->markTestSkipped('These tests are no longer required;');
        Mail::fake();

        $userDetails = $this->createCompleteRegistrationDetails();

        $userDetails['collection_type'] = 'collection';

        $response = $this->from($this->registerGetRoute())
            ->post(
                $this->registerPostRoute(),
                $userDetails
            );

        $users = User::all();

        $this->assertCount(1, $users);

        $response->assertRedirect($this->successfulRegistrationRoute());
    }

    /**
     * @test
     * @group Delivery
     */
    public function merchantWithDeliveryAndNoErrorWithoutDeliveryFields()
    {
        $this->markTestSkipped('These tests are no longer required;');
        Mail::fake();

        $userDetails = $this->createCompleteRegistrationDetails();

        $userDetails['collection_type'] = 'delivery';
        $userDetails['delivery_cost'] = '5.99';
        $userDetails['delivery_radius'] = '5';

        $response = $this->from($this->registerGetRoute())
            ->post(
                $this->registerPostRoute(),
                $userDetails
            );

        $users = User::all();

        $this->assertCount(1, $users);

        $response->assertRedirect($this->successfulRegistrationRoute());
    }

    /**
     * @test
     * @group Delivery
     */
    public function merchantWithBothAndNoErrorWithoutDeliveryFields()
    {
        $this->markTestSkipped('These tests are no longer required;');
        Mail::fake();

        $userDetails = $this->createCompleteRegistrationDetails();

        $userDetails['collection_type'] = 'both';
        $userDetails['delivery_cost'] = '5.99';
        $userDetails['delivery_radius'] = '5';

        $response = $this->from($this->registerGetRoute())
            ->post(
                $this->registerPostRoute(),
                $userDetails
            );

        $users = User::all();

        $this->assertCount(1, $users);

        $response->assertRedirect($this->successfulRegistrationRoute());
    }
}
