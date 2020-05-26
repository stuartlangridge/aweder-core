<?php

namespace App\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;

class DashboardSearchComponent extends Component
{
    /**
     * @var Collection
     */
    protected Collection $searchResults;

    public function __construct(Collection $searchResults)
    {
        $this->searchResults = $searchResults;
    }

    /*
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.dashboard-search-component')->with(['results' => $this->searchResults]);
    }
}
