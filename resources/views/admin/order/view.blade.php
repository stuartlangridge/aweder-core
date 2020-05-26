@extends('global.admin')
@section('content')
    <header class="dashboard__header col col--lg-12-10 col--sm-6-6 admin-inner-grid">
        <div class="dashboard__title col col--lg-12-6 col--sm-6-6">
            <span class="order__status order__status--new">New</span>
            <h1 class="header header--three color--carnation">Order #{{$order->url_slug}}</h1>
            <span class="order__delivery">{{ $order->getIsDeliveryOrCollection() }}</span>
        </div>
        <div class="dashboard__intro col col--lg-12-5 col--sm-6-5">
            <h3 class="header header--five color--cloudburst">{{ $merchant->name }}</h3>
            <p>{{ $merchant->address }}</p>
        </div>
    </header>
    <section class="merchant-order col col--lg-12-10 col--sm-6-6 admin-inner-grid">
        <div class="col col--lg-12-4 col--m-12-4 col--sm-6-6 merchant-order__order">
            <dl class="merchant-order__list">
                @foreach ($order->items as $item)
                    <dt>{{ $item->orderInventory->title }} x {{ $item->quantity }}</dt>
                    <dd>&pound;{{ $item->getOrderItemPriceByQuantity() }}</dd>
                @endforeach
                @if ($order->getIsDeliveryOrCollection() === 'Delivery')
                    <dt class="merchant-order__total">Delivery</dt>
                    <dd class="merchant-order__total-price">&pound;{{ $order->getFormattedUKPriceAttribute($merchant->delivery_cost) }}</dd>
                @endif
                <dt class="merchant-order__total">Total</dt>
                @if ($order->is_delivery === 1)
                    <dd class="merchant-order__total-price">&pound;{{$order->getFormattedUKPriceAttribute($order->total_cost, $order->delivery_cost)}}</dd>
                @else
                    <dd class="merchant-order__total-price">&pound;{{$order->getFormattedUKPriceAttribute($order->total_cost)}}</dd>
                @endif
            </dl>
            @if (!empty($order->customer_note))
                <div class="merchant-order__notes">
                    <span class="font--gibson-semi">Customer note</span>
                    <p>{{ $order->customer_note }}</p>
                </div>
            @endif
            <div class="merchant-order__delivery">
                <p>For {{ $order->getIsDeliveryOrCollection() }}</p>
                <ul>
                    <li>Name: {{ $order->customer_name }}</li>
                    <li>Address: {{ $order->customer_address }}</li>
                    <li>Phone number: {{ $order->customer_phone }}</li>
                    <li>Email address: {{ $order->customer_email }}</li>
                </ul>
            </div>
        </div>

        <div
            class="col col--lg-12-5 col--lg-offset-12-6 col--sm-6-6 col--sm-offset-6-1 merchant-order__acceptance form--background">
            @if (!$order->isOrderCompleted())
            <form class="form form--accept" method="POST"
                  action="{{ route('admin.accept-order', $order->url_slug) }}">
                @csrf
                <div class="field">
                    @error('time_hours')
                    <span class="form__validation-error">{{ $message }}</span>
                    @enderror
                    <span class="label">What time customer will be told the order is available</span>
                    <div class="select-wrapper">
                        <div class="field field--select">
                            <select name="time_hours" class="select">
                                @for ($i = 0; $i <= 23; $i++)
                                    <option value="{{ $i < 10 ? '' . $i : $i }}"
                                            @if (old('time_hours', (int) $predictedOrderTimeHour) === $i) selected @endif>{{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <span>:</span>
                        <div class="field field--select">
                            <select name="time_minutes" class="select">
                                @for ($i = 0; $i <= 12; $i++)
                                    <option value="{{ $i * 5 < 10 ? '0' . $i * 5 : $i * 5}}"
                                            @if (old('time_minutes', (int) $predictedOrderTimeMinute) === $i * 5) selected @endif>{{ $i * 5 < 10
                                        ? '0' . $i * 5 : $i * 5 }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <span class="label">Final order price</span>
                    <p>&pound; {{ $order->getFormattedUKPriceAttribute($order->total_cost, $order->delivery_cost) }}</p>
                </div>
                <div class="field field--textarea">
                    <label for="merchant-accept">Please add any notes you would like to send to the customer</label>
                    <textarea id="merchant-accept" name="merchant_note"></textarea>
                    @error('merchant_note')
                    <span class="form__validation-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field field--button">
                    <button class="button button--icon-right button--filled button--filled-carnation button--end">
                        <span class="button__content">Accept order</span>
                        <span class="icon icon-right">@svg('arrow-right')</span>
                    </button>
                </div>
            </form>
            <form class="form form--decline" method="POST"
                  action="{{ route('admin.reject-order', $order->url_slug) }}">
                @csrf
                <div class="field field--textarea">
                    <label for="merchant-accept">Reason to the customer for declined order</label>
                    <textarea id="merchant-accept" name="merchant_rejection_reason"></textarea>
                    @error('merchant_rejection_reason')
                        <span class="form__validation-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field field--button">
                    <button class="button button--icon-right button--outline button--outline-red button--end">
                        <span class="button__content">Reject order</span>
                        <span class="icon icon-right">@svg('arrow-right')</span>
                    </button>
                </div>
            </form>
            @elseif ($order->status === 'acknowledged')
                @if (!empty($order->merchant_note))
                    <p>Note to customer:</p>
                    <p>{{ $order->merchant_note }}</p>
                    <p>Available Time</p>
                    <p>{{ $order->getFormattedDateForEmail($order->available_time) }}</p>
                    <x-order-button-component :order="$order" />
                @endif
            @elseif ($order->status === 'unacknowledged')
                <p>This order was rejected for the following reason:</p>
                <p>{{ $order->rejection_reason }}</p>
            @endif
        </div>
    </section>
@endsection
