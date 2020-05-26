<?php

namespace Tests\Feature\Auth\Registration\MultiStep\BusinessDetails;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class DetailsPostControllerTest
 * @package Tests\Feature\Auth\Registration\MultiStep\BusinessDetails
 * @coversDefaultClass \App\Http\Controllers\Auth\Registration\MultiStep\BusinessDetails\DetailsPost
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

        $response = $this->from(route('register.business-details'))
            ->post(route('register.business-details.post'), $businessDetails);

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

        $response = $this->from(route('register.business-details'))
            ->post(route('register.business-details.post'), $businessDetails);

        $response->assertRedirect(route('register.business-details'));

        $response->assertSessionHasErrors('url-slug');
    }

    /**
     * @test
     *
     */
    public function userCanSubmitMerchantWithFullDetails()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $businessDetails = [
            'name' => $this->faker->safeEmail,
            'url-slug' => $this->faker->slug,
            'collection_type' => 'collection',
            'description' => $this->faker->words(10, true),
        ];

        $response = $this->from(route('register.business-details'))
            ->post(route('register.business-details.post'), $businessDetails);

        $response->assertRedirect(route('register.contact-details'));
    }

    /**
     * @test
     */
    public function userCantSubmitDeliveryChoiceWithoutDeliveryRadius()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $businessDetails = [
            'name' => $this->faker->safeEmail,
            'url-slug' => $this->faker->slug,
            'collection_type' => 'delivery',
        ];

        $response = $this->from(route('register.business-details'))
            ->post(route('register.business-details.post'), $businessDetails);

        $response->assertRedirect(route('register.business-details'));

        $response->assertSessionHasErrors('delivery_radius');
    }

    /**
     * @test
     */
    public function userCantSubmitDeliveryChoiceWithoutDeliveryCost()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $businessDetails = [
            'name' => $this->faker->safeEmail,
            'url-slug' => $this->faker->slug,
            'collection_type' => 'delivery',
            'delivery_radius' => 5
        ];

        $response = $this->from(route('register.business-details'))
            ->post(route('register.business-details.post'), $businessDetails);

        $response->assertRedirect(route('register.business-details'));

        $response->assertSessionHasErrors('delivery_cost');
    }

    /**
     * @test
     */
    public function userCanSubmitDeliveryChoiceWithAllDeliveryOptionsAsFloatCost()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $name = $this->faker->name;

        $slug = $this->faker->slug;

        $description = $this->faker->words(10, true);

        $businessDetails = [
            'name' => $name,
            'url-slug' => $slug,
            'collection_type' => 'delivery',
            'delivery_radius' => 5,
            'delivery_cost' => '5.99',
            'description' => $description,
        ];

        $response = $this->from(route('register.business-details'))
            ->post(route('register.business-details.post'), $businessDetails);

        $response->assertRedirect(route('register.contact-details'));

        $this->assertDatabaseHas(
            'merchants',
            [
                'url_slug' => $slug,
                'name' => $name,
                'allow_delivery' => 1,
                'allow_collection' => 0,
                'delivery_cost' => 599,
                'delivery_radius' => 5,
                'description' => $description,
                'registration_stage' => 3,
            ]
        );
    }


    /**
     * @test
     */
    public function userCanSubmitBothChoiceWithAllDeliveryOptionsAsFloatCost()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $name = $this->faker->name;

        $slug = $this->faker->slug;

        $description = $this->faker->words(10, true);

        $businessDetails = [
            'name' => $name,
            'url-slug' => $slug,
            'collection_type' => 'both',
            'delivery_radius' => 5,
            'delivery_cost' => '5.99',
            'description' => $description,
        ];

        $response = $this->from(route('register.business-details'))
            ->post(route('register.business-details.post'), $businessDetails);

        $response->assertRedirect(route('register.contact-details'));

        $this->assertDatabaseHas(
            'merchants',
            [
                'url_slug' => $slug,
                'name' => $name,
                'description' => $description,
                'allow_delivery' => 1,
                'allow_collection' => 1,
                'delivery_cost' => 599,
                'delivery_radius' => 5,
                'registration_stage' => 3,
            ]
        );
    }

    /**
     * @test
     */
    public function userCantSubmitBothChoiceWithRadiusMissing()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $name = $this->faker->name;

        $slug = $this->faker->slug;

        $businessDetails = [
            'name' => $name,
            'url-slug' => $slug,
            'collection_type' => 'both',
            'delivery_cost' => '5.99',
        ];

        $response = $this->from(route('register.business-details'))
            ->post(route('register.business-details.post'), $businessDetails);

        $response->assertRedirect(route('register.business-details'));

        $response->assertSessionHasErrors('delivery_radius');
    }

    /**
     * @test
     */
    public function userCantSubmitBothChoiceWithCostMissing()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $name = $this->faker->name;

        $slug = $this->faker->slug;

        $businessDetails = [
            'name' => $name,
            'url-slug' => $slug,
            'collection_type' => 'both',
            'delivery_radius' => 5,
        ];

        $response = $this->from(route('register.business-details'))
            ->post(route('register.business-details.post'), $businessDetails);

        $response->assertRedirect(route('register.business-details'));

        $response->assertSessionHasErrors('delivery_cost');
    }

    /**
     * @test
     */
    public function userCantSubmitWithMoreThanOneHundredAndFourtyWordDescription()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $name = $this->faker->name;

        $slug = $this->faker->slug;

        $businessDetails = [
            'name' => $name,
            'url-slug' => $slug,
            'collection_type' => 'collection',
            'description' => $this->faker->words(140, true),
        ];

        $response = $this->from(route('register.business-details'))
            ->post(route('register.business-details.post'), $businessDetails);

        $response->assertRedirect(route('register.business-details'));

        $response->assertSessionHasErrors('description');
    }
}
