@extends('global.app')
@section('content')
    <section class="ordering">
        <div class="row">
            <div class="content">
                <div class="col--lg-12-7 col--sm-6-6 form--background">
                    <header class="section-title">
                        <h3 class="header header--five color--carnation">Thank you</h3>
                    </header>
                    <p>Thanks for placing your order, we’ve now been notified and will email you ASAP to confirm that its being processed and what time it can be collected or delivered.</p>
                    <p>Any problems then please do get in touch with us on
                        <a href="tel:{{$merchant->contact_number}}">{{ $merchant->contact_number }}</a>
                </div>
                <div
                    class="col--lg-12-4 col--lg-offset-12-9 col--m-12-5 col--m-offset-12-8 col--sm-6-6 col--sm-offset-6-1 order">
                    <header class="order__header">
                        <h3 class="header header--five">You ordered</h3>
                    </header>
                    @if (isset($order))
                        @if (!$order->items->isEmpty())
                            @foreach ($order->items as $item)
                                <div class="order__menu">
                                    <dl class="order__menu-list">
                                        <dd class="order__title">{{ $item->inventory->title }}</dd>
                                        <dd class="order__price">{{ $item->quantity }} *
                                            &pound;{{ $item->getFormattedUKPriceAttribute($item->price) }}</dd>
                                    </dl>
                                </div>
                            @endforeach
                            @if(!$order->customer_note !== null)
                                <div class="order__note">
                                    <p><span>Order Note:</span>{{$order->customer_note}}</p>
                                </div>
                            @endif
                            <div class="order__total">
                                @if ($order->is_delivery === 1)
                                    <p><span>Delivery:</span> £{{ $merchant->getFormattedUKPriceAttribute($merchant->delivery_cost) }}</p>
                                    <p><span>Total:</span>
                                        £{{ $order->getFormattedUKPriceAttribute($order->total_cost, $merchant->delivery_cost) }}</p>
                                @else
                                    <p><span>Total:</span> &pound;{{ $order->getFormattedUKPriceAttribute($order->total_cost) }}</p>
                                @endif

                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
