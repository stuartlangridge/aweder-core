<?php

namespace Tests\Unit\Repository;

use App\Contract\Repositories\MerchantContract;
use App\Merchant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Hash;

/**
 * Class MerchantRepositoryTest
 * @package Tests\Unit\Repository
 * @group Merchant
 * @coversDefaultClass \App\Providers\Repositories\MerchantServiceProvider
 */
class MerchantRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var MerchantContract
     */
    protected $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = app()->make(MerchantContract::class);
    }

    public function testCreateMerchantWillMakeMerchant(): void
    {
        $user = factory(User::class)->create([
            'email' => 'test@test.com',
            'password' => Hash::make('password')
        ]);

        $registrationData = [
            'url-slug' => 'test-slug',
            'name' => 'test-name',
            'customer-phone-number' => '01234567890',
            'address-name-number' => '1',
            'address-street' => 'test street',
            'address-locality' => 'test locality',
            'address-city' => 'test city',
            'address-county' => 'test county',
            'address-postcode' => 'TE5T11',
            'mobile-number' => '01234567890',
            'collection_type' => 'both',
            'delivery_cost' => 0,
            'delivery_radius' => 0,
        ];

        $this->assertDatabaseMissing(
            'merchants',
            [
                'url_slug' => 'test-slug',
                'name' => 'test-name',
                'contact_number' => '01234567890',
                'mobile_number' => '01234567890',
            ]
        );

        $merchant = $this->repository->createMerchant($user, $registrationData);

        $this->assertInstanceOf(Merchant::class, $merchant);
    }

    public function testUpdateMerchantWillUpdateMerchant(): void
    {
        $user = factory(User::class)->create([
            'email' => 'test@test.com',
            'password' => Hash::make('password')
        ]);

        $merchant = factory(Merchant::class)->create();

        $user->merchants()->attach($merchant->id);

        $this->actingAs($user);

        $registrationData = [
            'customer-phone-number' => '01234567890',
            'address-name-number' => '1',
            'address-street' => 'test street',
            'address-locality' => 'test locality',
            'address-city' => 'test city',
            'address-county' => 'test county',
            'address-postcode' => 'TE5T11',
            'mobile-number' => '01234567890',
            'collection_type' => 'both',
            'contact_email' => $merchant->users()->first()->email,
            'delivery_cost' => '4.99'
        ];

        $this->assertDatabaseMissing(
            'merchants',
            [
                'contact_number' => '01234567890',
                'address_street' => 'test street',
                'address_city' => 'test city',
                'address_county' => 'test county',
                'address_postcode' => 'TE5T11',
                'mobile_number' => '01234567890',
                'delivery_cost' => 499
            ]
        );

        $merchant = $this->repository->updateMerchant($merchant, $registrationData);

        $this->assertDatabaseHas(
            'merchants',
            [
                'contact_number' => '01234567890',
                'address_street' => 'test street',
                'address_city' => 'test city',
                'address_county' => 'test county',
                'address_postcode' => 'TE5T11',
                'mobile_number' => '01234567890',
                'delivery_cost' => 499
            ]
        );

        $this->assertTrue($merchant);
    }
    public function testUpdateMerchantWithAlreadyExistingDeliveryCostBeingUpdated(): void
    {
        $user = factory(User::class)->create([
            'email' => 'test@test.com',
            'password' => Hash::make('password')
        ]);

        $merchant = factory(Merchant::class)->create([
            'delivery_cost' => 249
        ]);

        $user->merchants()->attach($merchant->id);

        $this->actingAs($user);

        $registrationData = [
            'delivery_cost' => '3.49'
        ];

        $this->assertDatabaseHas(
            'merchants',
            [
                'delivery_cost' => 249
            ]
        );

        $this->assertDatabaseMissing(
            'merchants',
            [
                'delivery_cost' => 349
            ]
        );

        $merchant = $this->repository->updateMerchant($merchant, $registrationData);

        $this->assertDatabaseHas(
            'merchants',
            [
                'delivery_cost' => 349
            ]
        );
        $this->assertDatabaseMissing(
            'merchants',
            [
                'delivery_cost' => 249
            ]
        );

        $this->assertTrue($merchant);
    }
    public function testUpdateMerchantWithAlreadyExistingDeliveryCostNotBeingUpdated(): void
    {
        $user = factory(User::class)->create([
            'email' => 'test@test.com',
            'password' => Hash::make('password')
        ]);

        $merchant = factory(Merchant::class)->create([
            'delivery_cost' => 249
        ]);

        $user->merchants()->attach($merchant->id);

        $this->actingAs($user);

        $this->assertDatabaseHas(
            'merchants',
            [
                'delivery_cost' => 249
            ]
        );

        $registrationData = [
            'customer-phone-number' => '01234567890',
            'address-name-number' => '1',
            'address-street' => 'test street',
            'address-locality' => 'test locality',
            'address-city' => 'test city',
            'address-county' => 'test county',
            'address-postcode' => 'TE5T11',
            'mobile-number' => '01234567890',
            'collection_type' => 'both',
            'contact_email' => $merchant->users()->first()->email,
        ];

        $merchant = $this->repository->updateMerchant($merchant, $registrationData);

        $this->assertDatabaseHas(
            'merchants',
            [
                'delivery_cost' => 249
            ]
        );

        $this->assertTrue($merchant);
    }
}
