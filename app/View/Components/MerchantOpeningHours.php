<?php

namespace App\View\Components;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class MerchantOpeningHours extends Component
{
    public Collection $openingHours;

    public array $dayOfWeek = [
        '1' => 'Monday',
        '2' => 'Tuesday',
        '3' => 'Wednesday',
        '4' => 'Thursday',
        '5' => 'Friday',
        '6' => 'Saturday',
        '7' => 'Sunday',
    ];

    /**
     * Create a new component instance.
     *
     * @param Collection $openingHours
     */
    public function __construct(Collection $openingHours)
    {
        $this->openingHours = $openingHours;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.merchant-opening-hours');
    }

    /**
     * returns the store status right now
     * @return string
     */
    public function storeOpenStatus(): string
    {
        if ($this->openingHours->isEmpty()) {
            return 'Closed';
        }

        $todaysDay = Carbon::now()->dayOfWeekIso;

        $todaysOpeningTimes = $this->openingHours->where('day_of_week', '=', $todaysDay)->first();

        if ($todaysOpeningTimes === null) {
            return 'Closed';
        }

        $todaysCurrentHour = strtotime(Carbon::now()->setTimezone('Europe/London')->toDateTimeLocalString());

        $todaysOpeningHour = strtotime(
            Carbon::createFromTimeString($todaysOpeningTimes->open_time->format('H:i'))->toDateTimeLocalString()
        );

        $todaysClosingHour = strtotime(
            Carbon::createFromTimeString($todaysOpeningTimes->close_time->format('H:i'))->toDateTimeLocalString()
        );

        if ($todaysCurrentHour >= $todaysOpeningHour && $todaysCurrentHour <= $todaysClosingHour) {
            return 'Open';
        }

        return 'Closed';
    }
}
