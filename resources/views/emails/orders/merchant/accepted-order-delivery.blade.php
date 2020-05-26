<p>Hi,</p>
<p>Thanks again for ordering,</p>
<p>we're delighted to confirm your order is now processing and will be or delivered at approximately {{ $order->getFormattedDateForEmail($order->available_time) }}.</p>
<p>We'll be preparing:</p>
@foreach ($order->items as $item)
    <p>{{ $item->inventory->title }} * {{ $item->quantity }} - {{ $item->getOrderItemPriceByQuantity() }}</p>
@endforeach
<p>Order Delivery Cost: &pound;{{ $order->getFormattedUKPriceAttribute($order->delivery_cost) }}</p>
<p>Order Total: &pound;{{ $order->getFormattedUKPriceAttribute($order->total_cost, $order->delivery_cost) }}</p>
@if ($order->merchant_note !== null || !empty($order->merchant_note))
    <p>Merchant Note: </p>
    <p>{{ $order->merchant_note }}</p>
@endif
<p>If you need to contact us then please call on <a href="tel:{{ $merchant->contact_number }}">{{ $merchant->contact_number }}</a></p>
<p>Best regards, {{$merchant->title }}</p>
