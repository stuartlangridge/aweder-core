<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DashboardOrderFilteringComponent extends Component
{
    protected ?string $filteredStatus;

    protected string $period;

    /**
     * Create a new component instance.
     *
     * @param string $period
     * @param string|null $filteredStatus
     */
    public function __construct(
        string $period,
        ?string $filteredStatus
    ) {
        $this->filteredStatus = $filteredStatus;

        $this->period = $period;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.dashboard-order-filtering-component')
            ->with('current', $this->period)
            ->with('status', $this->filteredStatus);
    }
}
