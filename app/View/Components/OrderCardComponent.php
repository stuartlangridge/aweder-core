<?php

namespace App\View\Components;

use App\Order;
use Illuminate\View\Component;

class OrderCardComponent extends Component
{
    protected Order $order;

    /**
     * Create a new component instance.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.order-card-component')
            ->with('order', $this->order);
    }
}
