<p>Hi,</p>
<p>Thank you for placing your order with {{ $merchant->name }}.</p>
<p>This is to confirm that we have received your order. The next step is for us to confirm that it has been accepted and let you know the timings.</p>
<p>You’ll receive another email as soon as we do this.</p>
<p>You have ordered:</p>
@foreach ($order->items as $item)
    <p>{{ $item->inventory->title }} * {{ $item->quantity }} -  &pound;{{ $item->getOrderItemPriceByQuantity() }}</p>
@endforeach
@if ($order->customer_note !== null || !empty($order->customer_note))
    <p>Order Note:</p>
    <p>{{ $order->customer_note }}
@endif
@if ($order->is_delivery === 1)
    <p>Order Total: &pound;{{ $order->getFormattedUKPriceAttribute($order->total_cost, $merchant->delivery_cost) }}</p>
@else
    <p>Order Total: &pound;{{ $order->getFormattedUKPriceAttribute($order->total_cost) }}</p>
@endif
<p>If you need to contact us then please call on {{ $merchant->customer_phone_number }}.</p>
<p>Best regards, {{ $merchant->name }}</p>
