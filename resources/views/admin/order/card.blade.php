<a class="outstanding-orders__order col col--lg-12-4 col--l-12-4 col--m-12-6 col--sm-6-6 col--s-6-6 form--background"
   href="{{ route('admin.view-order', $order->url_slug) }}">
    <header class="outstanding-orders__header">
        <h3 class="header font--kepler-med">Order #{{$order->url_slug}}</h3>
        <span class="order__status order__status--new">{{$order->order_submitted->format('d M, H:i')}}</span>
    </header>
    <dl class="outstanding-orders__details">
        <dt>Delivery type</dt>
        <dd>{{$order->getIsDeliveryOrCollection()}}</dd>
        <dt>Customer</dt>
        <dd>{{$order->customer_name}}</dd>
        <dt>Customer email</dt>
        <dd>{{$order->customer_email}}</dd>
        <dt>Customer address</dt>
        <dd>{{$order->customer_address}}</dd>
        <dt>Customer phone no</dt>
        <dd>{{$order->customer_phone}}</dd>
    </dl>
    <div class="outstanding-orders__notes">
        <h4>Customer Notes</h4>
        @if(!empty($order->customer_note) && $order->customer_note !== null)
            <p>{{ $order->customer_note }}</p>
        @endif
    </div>
    <div class="outstanding-orders__price">
        <h4>Price</h4>
        @if ($order->is_delivery === 1)
            <span class="font--kepler-med">&pound;{{$order->getFormattedUKPriceAttribute($order->total_cost, $order->delivery_cost)}}</span>
        @else
            <span class="font--kepler-med">&pound;{{$order->getFormattedUKPriceAttribute($order->total_cost)}}</span>
        @endif
    </div>
    <span class="outstanding-orders__view">View order</span>
</a>
