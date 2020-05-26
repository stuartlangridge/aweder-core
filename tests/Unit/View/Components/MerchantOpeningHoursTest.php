<?php

namespace Tests\Unit\View\Components;

use App\View\Components\MerchantOpeningHours;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class MerchantOpeningHoursTest
 * @package Tests\Unit\View\Components
 * @coversDefaultClass \App\View\Components\MerchantOpeningHours
 * @group Component
 */
class MerchantOpeningHoursTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     */
    public function checkStoreOpenStatusShowsClosedWhenStoreIsClosedWhenNoDayOfWeekExists()
    {
        $merchant = $this->createAndReturnMerchant();

        $openingHours = $this->createAndReturnOpeningHoursForMerchant(
            [
                'merchant_id' => $merchant->id,
                'day_of_week' => 1
            ]
        );

        $openingHoursCollection = new Collection($openingHours);

        $knownDate = Carbon::create(2020, 4, 4);

        Carbon::setTestNow($knownDate);

        $component = new MerchantOpeningHours($openingHoursCollection);

        $result = $component->storeOpenStatus();

        $this->assertSame('Closed', $result);
    }

    /**
     * @test
     * @group Second
     */
    public function checkStoreOpenStatusShowsClosedWhenStoreIsClosedWhenTimeIsOutOfHours()
    {
        $merchant = $this->createAndReturnMerchant();

        $openingHours = $this->createAndReturnOpeningHoursForMerchant(
            [
                'merchant_id' => $merchant->id,
                'day_of_week' => 6
            ]
        );

        $openingHoursCollection = new Collection([$openingHours]);

        $openingHours = $this->createAndReturnOpeningHoursForMerchant(
            [
                'merchant_id' => $merchant->id,
                'day_of_week' => 7
            ]
        );

        $openingHoursCollection->add($openingHours);

        $knownDate = Carbon::create(2020, 4, 4, 23);

        Carbon::setTestNow($knownDate);

        $component = new MerchantOpeningHours($openingHoursCollection);

        $result = $component->storeOpenStatus();

        $this->assertSame('Closed', $result);
    }

    /**
     * @test
     * @group Second
     */
    public function checkStoreOpenStatusShowsClosedWhenStoreIsClosedWhenTimeIsInHours()
    {
        $merchant = $this->createAndReturnMerchant();

        $openingHours = $this->createAndReturnOpeningHoursForMerchant(
            [
                'merchant_id' => $merchant->id,
                'day_of_week' => 6
            ]
        );

        $openingHoursCollection = new Collection([$openingHours]);

        $openingHours = $this->createAndReturnOpeningHoursForMerchant(
            [
                'merchant_id' => $merchant->id,
                'day_of_week' => 7
            ]
        );

        $openingHoursCollection->add($openingHours);

        $knownDate = Carbon::create(2020, 4, 4, 10);

        Carbon::setTestNow($knownDate);

        $component = new MerchantOpeningHours($openingHoursCollection);

        $result = $component->storeOpenStatus();

        $this->assertSame('Open', $result);
    }
}
