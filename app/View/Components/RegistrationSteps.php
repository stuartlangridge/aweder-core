<?php

namespace App\View\Components;

use Illuminate\View\Component;

class RegistrationSteps extends Component
{
    public string $stage;

    /**
     * Create a new component instance.
     *
     * @param string $stage
     */
    public function __construct(string $stage)
    {
        $this->stage = $stage;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.registration-steps');
    }
}
