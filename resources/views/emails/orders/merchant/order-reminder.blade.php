<p>Quick reminder, the following order has not been confirmed by you yet, if this is not done by <CancelTime> then we'll have to cancel the order. Please hurry and visit the <a href="{{ route('admin.dashboard') }}">Orders Dashboard</a>!</p>
<p>To remind you of the order:</p>
<p>{{ $order->customer_name }} would like:</p>
@foreach ($order->items as $item)
    <p>{{ $item->inventory->title }} * {{ $item->quantity }} - &pound;{{ $item->getOrderItemPriceByQuantity() }}</p>
@endforeach
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
<p>To accept or reject this order, please visit your <a href="{{ route('admin.dashboard') }}">Orders Dashboard</a>
