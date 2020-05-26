<?php

namespace App\View\Components;

use App\Inventory;
use App\Merchant;
use App\Order;
use Illuminate\View\Component;

class DisplayItem extends Component
{
    public Inventory $item;

    public Merchant $merchant;

    public ?Order $order;

    public bool $editable;

    /**
     * Create a new component instance.
     *
     * @param Inventory $item
     * @param Merchant $merchant
     * @param bool $editable
     * @param Order|null $order
     */
    public function __construct(Inventory $item, Merchant $merchant, bool $editable, ?Order $order)
    {
        $this->item = $item;

        $this->merchant = $merchant;

        $this->order = $order;

        $this->editable = $editable;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view(
            'components.store.display-item',
            [
                'item' => $this->item,
                'merchant' => $this->merchant,
                'order' => $this->order,
                'editable' => $this->editable,
            ]
        );
    }
}
