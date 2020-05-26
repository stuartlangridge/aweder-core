<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Order
 *
 * @property int $id
 * @property string $url_slug
 * @property int $merchant_id
 * @property string|null $status
 * @property int $is_delivery
 * @property string|null $customer_name
 * @property string|null $customer_email
 * @property string|null $customer_address
 * @property string|null $customer_phone
 * @property string|null $available_time
 * @property string|null $rejection_reason
 * @property int|null $total_cost
 * @property int|null $final_cost
 * @property string|null $merchant_note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read float $formatted_u_k_price
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OrderItem[] $items
 * @property-read int|null $items_count
 * @property-read \App\Merchant $merchant
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereAvailableTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCustomerAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCustomerEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCustomerPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereFinalCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereIsDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereMerchantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereMerchantNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereRejectionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereTotalCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereUrlSlug($value)
 * @mixin \Eloquent
 * @property string|null $customer_note
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCustomerNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order status($value)
 * @property string|null $order_submitted
 * @property string|null $customer_requested_time
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCustomerRequestedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereOrderSubmitted($value)
 * @property int $order_id
 * @property int|null $reminder_time This is the minute reminder time
 * which should allow us to stop duplication being sent
 * @property string|null $sent
 * @property-read \App\Order $order
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderReminder whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderReminder whereReminderTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderReminder whereSent($value)
 */
class OrderReminder extends Model
{

    protected $fillable = [
        'order_id',
        'reminder_time',
        'sent',
    ];

    /**
     * a order item belongs to a single order
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
