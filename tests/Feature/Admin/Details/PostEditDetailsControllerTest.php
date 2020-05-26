<?php

namespace Tests\Feature\Admin\Details;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Class PostEditDetailsControllerTest
 * @package Tests\Feature\Admin\Details
 * @coversDefaultClass \App\Http\Controllers\Admin\Details\PostEditDetailsController
 * @group PostEditDelete
 */
class PostEditDetailsControllerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * @test
     */
    public function merchantTriesToUpdateWithOutBeingAuthorised()
    {
        $user = factory(User::class)->create();

        $merchantOne = $this->createAndReturnMerchant([
            'registration_stage' => 0,
            'description' => 'tester description',
        ]);

        $user->merchants()->attach($merchantOne->id);

        $postData = [
            'description' => 'updated description',
        ];

        $response = $this->post(
            'admin/edit-details',
            $postData
        );

        $response->assertStatus(Response::HTTP_FOUND);

        $response->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function authorisedMerchantCanUpdateDetails()
    {
        $user = factory(User::class)->create();

        $merchantOne = $this->createAndReturnMerchant([
            'registration_stage' => 0,
            'description' => 'tester description',
        ]);

        $user->merchants()->attach($merchantOne->id);

        $this->actingAs($user);

        $postData = [
            'description' => 'updated description',
            'customer-phone-number' => $merchantOne->contact_number,
            'collection_type' => 'both',
            'delivery_cost' => 5.99,
            'delivery_radius'=> 20,
        ];

        $response = $this->from('admin/edit-details')->post(
            'admin/edit-details',
            $postData
        );

        $response->assertRedirect('/admin/dashboard');

        $this->assertDatabaseHas(
            'merchants',
            [
                'id' => $merchantOne->id,
                'description' => 'updated description',
            ]
        );
    }

    /**
     * @test
     */
    public function merchantUploadsLogoWhenNoLogExists()
    {
        Storage::fake('s3');

        $testerFileName = '/tester/tester-file.png';

        $uploadedFile = UploadedFile::fake()->image($testerFileName, 100, 100)->size(100);

        $user = factory(User::class)->create();

        $merchantOne = $this->createAndReturnMerchant([
            'registration_stage' => 0,
            'description' => 'tester description',
            'logo' => null,
        ]);

        $user->merchants()->attach($merchantOne->id);

        $this->actingAs($user);

        $postData = [
            'description' => 'updated description',
            'customer-phone-number' => $merchantOne->contact_number,
            'collection_type' => 'both',
            'delivery_cost' => 5.99,
            'delivery_radius' => 20,
            'logo' => $uploadedFile,
        ];

        $this->assertDatabaseHas(
            'merchants',
            [
                'id' => $merchantOne->id,
                'description' => 'tester description',
                'logo' => null,
            ]
        );

        $response = $this->from('admin/edit-details')->post(
            'admin/edit-details',
            $postData
        );

        $response->assertRedirect('/admin/dashboard');

        $this->assertDatabaseMissing(
            'merchants',
            [
                'id' => $merchantOne->id,
                'description' => 'updated description',
                'logo' => $testerFileName,
            ]
        );
    }
}
