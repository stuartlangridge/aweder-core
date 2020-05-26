<?php

namespace App;

use App\Traits\HelperTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\OrderItem
 *
 * @property int $id
 * @property int $order_id
 * @property int $quantity
 * @property int $price The price is in pence
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $inventory_id
 * @property-read \App\Inventory $inventory
 * @property-read \App\Order $order
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereInventoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Inventory $orderInventory
 * @property-read string $formatted_u_k_price
 */
class OrderItem extends Model
{
    use HelperTrait;

    protected $fillable = [
        'order_id',
        'quantity',
        'price',
        'inventory_id',
    ];

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return BelongsTo
     */
    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    /**
     * @return BelongsTo
     */
    public function orderInventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class, 'inventory_id')->withTrashed();
    }

    /**
     * returns a overall price for the item devided by quantity
     * @return string
     */
    public function getOrderItemPriceByQuantity(): string
    {
        return number_format((($this->price * $this->quantity) / 100), 2);
    }
}
