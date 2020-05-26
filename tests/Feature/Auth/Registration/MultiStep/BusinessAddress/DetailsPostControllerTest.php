<?php

namespace Tests\Feature\Auth\Registration\MultiStep\BusinessAddress;

use App\Mail\Aweder\MerchantConfirmationSignUpEmail;
use App\Mail\Merchant\SignUpEmail;
use App\Mail\MerchantSignUp;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Class DetailsPostControllerTest
 * @package Tests\Feature\Auth\Registration\MultiStep\BusinessAddress
 * @coversDefaultClass \App\Http\Controllers\Auth\Registration\MultiStep\BusinessAddress\DetailsPost
 * @group MultiStageRegistration
 */
class DetailsPostControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     *
     */
    public function userCantAccessPageWithoutBeingAuthorised()
    {
        $businessDetails = [
            'email' => $this->faker->safeEmail
        ];

        $response = $this->from(route('register.business-address'))
            ->post(route('register.business-address.post'), $businessDetails);

        $response->assertRedirect(route('login'));
    }

    /**
     * @test
     *
     */
    public function userCanAccessAsAuthedUserButRedirectedBackWhenMissingDetails()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $businessDetails = [
            'name' => $this->faker->safeEmail,
        ];

        $response = $this->from(route('register.business-address'))
            ->post(route('register.business-address.post'), $businessDetails);

        $response->assertRedirect(route('register.business-address'));

        $response->assertSessionHasErrors('address-name-number');
    }

    /**
     * @test
     * @group Test
     */
    public function userCanSubmitMerchantWithFullDetails()
    {
        Mail::fake();

        $user = factory(User::class)->create();

        $merchant = $this->createAndReturnMerchant(['registration_stage' => 4]);

        $user->merchants()->attach($merchant->id);

        $this->actingAs($user);

        $addressName = $this->faker->secondaryAddress;
        $addressStreet = $this->faker->streetAddress;
        $addressLocality = $this->faker->city;
        $addressCity = $this->faker->city;
        $addressCounty = $this->faker->county;
        $addressPostCode = $this->faker->postcode;

        $businessDetails = [
            'address-name-number' => $addressName,
            'address-street' => $addressStreet,
            'address-locality' => $addressLocality,
            'address-city' => $addressCity,
            'address-county' => $addressCounty,
            'address-postcode' => $addressPostCode,
        ];

        $response = $this->post(route('register.business-address.post'), $businessDetails);

        $response->assertRedirect(route('registration.opening-hours'));

        $this->assertDatabaseHas(
            'merchants',
            [
                'address_name_number' => $addressName,
                'address_street' => $addressStreet,
                'address_city' => $addressCity,
                'address_county' => $addressCounty,
                'address_postcode' => $addressPostCode,
                'registration_stage' => 0,
            ]
        );

        Mail::assertSent(MerchantSignUp::class);
    }
}
