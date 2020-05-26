<?php

namespace App\Rules;

use App\Contract\Repositories\OrderContract;
use Illuminate\Contracts\Validation\Rule;

class DoesMerchantSupportCollectionType implements Rule
{
    /**
     * @var \App\Order|null
     */
    protected $order;

    /**
     * @var string|null
     */
    protected $orderSlug;

    /**
     * @var OrderContract|mixed
     */
    protected $orderRepo;

    /**
     * Create a new rule instance.
     *
     * @param string|null $orderSlug
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(?string $orderSlug)
    {
        $this->orderRepo = app()->make(OrderContract::class);

        $this->orderSlug = $orderSlug;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->orderSlug === null) {
            return false;
        }

        $this->order = $this->orderRepo->getOrderByOrderNo($this->orderSlug);

        if ($this->order !== null) {
            return $this->order->merchant->doesMerchantSupportDeliveryType($value);
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The current merchant doesn\'t support your choice of delivery/collection';
    }
}
