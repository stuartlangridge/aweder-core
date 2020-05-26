<?php

namespace Tests\Feature\Traits;

use App\Merchant;
use App\Order;
use App\Traits\TrackChanges;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionClass;
use ReflectionException;
use Tests\TestCase;
use Tests\Traits\Helpers;

/**
 * Class HelperTraitTest
 * @group Trait
 * @group TrackChangesTrait
 * @package Tests\Feature\Traits
 */
class TrackChangesTest extends TestCase
{
    use RefreshDatabase;
    use Helpers;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     *
     * @throws ReflectionException
     */
    public function track_changes_for_created_event(): void
    {
        $user = factory(User::class)->create();

        $merchant = factory(Merchant::class)->create();

        $order = factory(Order::class)->raw([
            'id' => 1,
            'status' => 'unacknowledged'
        ]);

        $user->merchants()->attach($merchant->id);

        $this->actingAs($user);

        $model = new class extends Model {
            use TrackChanges;

            protected static $tracking_events = [
                'created'
            ];
            protected static $filtered_data = [
                'status'
            ];
        };

        $model->setRawAttributes($order);

        $result = $model->trackChanges('created');

        $this->assertTrue($result);

        $data = json_encode(['status' => $order['status']]);

        $this->assertDatabaseHas(
            'tracked_changes',
            [
                'subject_id' => $order['id'],
                'subject_type' => get_class($model),
                'user_id' => $user->id,
                'name' => 'created' . '_' . strtolower((new ReflectionClass($model))->getShortName()),
                'data' => $this->castToJson($data)
            ]
        );
    }

    /**
     * @test
     *
     * @throws ReflectionException
     */
    public function track_changes_for_deleted_event(): void
    {
        $user = factory(User::class)->create();

        $merchant = factory(Merchant::class)->create();

        $order = factory(Order::class)->raw([
            'id' => 1,
            'status' => 'unacknowledged'
        ]);

        $user->merchants()->attach($merchant->id);

        $this->actingAs($user);

        $model = new class extends Model {
            use TrackChanges;

            protected static $tracking_events = [
                'deleted'
            ];
            protected static $filtered_data = [
                'status'
            ];
        };

        $model->setRawAttributes($order);

        $result = $model->trackChanges('deleted');

        $this->assertTrue($result);

        $data = json_encode(['status' => $order['status']]);

        $this->assertDatabaseHas(
            'tracked_changes',
            [
                'subject_id' => $order['id'],
                'subject_type' => get_class($model),
                'user_id' => $user->id,
                'name' => 'deleted' . '_' . strtolower((new ReflectionClass($model))->getShortName()),
                'data' => $this->castToJson($data)
            ]
        );
    }

    /**
     * @test
     *
     * @throws ReflectionException
     */
    public function track_changes_for_updated_event(): void
    {
        $user = factory(User::class)->create();

        $merchant = factory(Merchant::class)->create();

        $order = factory(Order::class)->raw([
            'id' => 1,
            'status' => 'unacknowledged'
        ]);

        $user->merchants()->attach($merchant->id);

        $this->actingAs($user);

        $model = new class extends Model {
            use TrackChanges;

            protected static $tracking_events = [
                'updated'
            ];
            protected static $filtered_data = [
                'status'
            ];
        };

        $model->setRawAttributes($order);

        $result = $model->trackChanges('updated');

        $this->assertTrue($result);

        $data = json_encode(['status' => $order['status']]);

        $this->assertDatabaseHas(
            'tracked_changes',
            [
                'subject_id' => $order['id'],
                'subject_type' => get_class($model),
                'user_id' => $user->id,
                'name' => 'updated' . '_' . strtolower((new ReflectionClass($model))->getShortName()),
                'data' => $this->castToJson($data)
            ]
        );
    }

    /**
     * @test
     *
     * @throws ReflectionException
     */
    public function track_changes_for_model_with_no_filtered_data(): void
    {
        $user = factory(User::class)->create();

        $merchant = factory(Merchant::class)->create();

        $order = factory(Order::class)->raw([
            'id' => 1,
            'status' => 'unacknowledged'
        ]);

        $user->merchants()->attach($merchant->id);

        $this->actingAs($user);

        $model = new class extends Model {
            use TrackChanges;

            protected static $tracking_events = [
                'updated'
            ];
        };

        $model->setRawAttributes($order);

        $result = $model->trackChanges('updated');

        $this->assertFalse($result);

        $data = json_encode(['status' => $order['status']]);

        $this->assertDatabaseMissing(
            'tracked_changes',
            [
                'subject_id' => $order['id'],
                'subject_type' => get_class($model),
                'user_id' => $user->id,
                'name' => 'updated' . '_' . strtolower((new ReflectionClass($model))->getShortName()),
                'data' => $this->castToJson($data)
            ]
        );
    }
}
