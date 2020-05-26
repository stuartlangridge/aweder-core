<p>Another order placed!</p>
<p>{{ $order->customer_name }} would like:</p>
@foreach ($order->items as $item)
    <p>{{ $item->inventory->title }} * {{ $item->quantity }} - &pound;{{ $item->getOrderItemPriceByQuantity() }}</p>
@endforeach
<p>Order Requested for: {{$order->getFormattedDateForEmail($order->customer_requested_time)}}</p>
@if ($order->is_delivery === 1)
    <p>Order Total: &pound;{{ $order->getFormattedUKPriceAttribute($order->total_cost, $merchant->delivery_cost) }}</p>
@else
    <p>Order Total: &pound;{{ $order->getFormattedUKPriceAttribute($order->total_cost) }}</p>
@endif
<p>For {{ $order->orderType() }}</p>
@if ($order->customer_note !== null || !empty($order->customer_note))
    <p>Customer Note:</p>
    <p>{{ $order->customer_note }}
@endif
<p>To accept or reject this order, please <a href="{{ route('admin.view-order', $order->url_slug) }}">view the order</a></p>
