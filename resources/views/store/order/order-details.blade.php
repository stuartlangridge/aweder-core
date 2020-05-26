@extends('global.app')
@section('content')
    <section class="ordering">
        <div class="row">
            <div class="content">
                <div class="col--lg-12-7 col--sm-6-6 form--background">
                    <header class="section-title">
                        <h1 class="header header--five color--carnation ordering__title">Order details</h1>
                        <h2 class="header color--cloudburst ordering__merchant">{{ $merchant->name }}</h2>
                        <p class="ordering__address">{{ $merchant->address }}</p>
                        <p class="ordering__tel"><a href="tel:{{ $merchant->contact_number }}">{{ $merchant->contact_number }}</a></p>
                        <p class="ordering__type">You have requested a
                            <span>
                                @if ($order->is_delivery)
                                    delivery
                                @else
                                    collection
                                @endif
                            </span>
                            for <span>{{ $order->getFormattedDeliveryTime($order->customer_requested_time) }}</span>.</p>
                    </header>
                    <p class="ordering__step">Nearly there, please fill in your details below and click 'Confirm order details.'</p>
                    <form
                        action="{{ route('store.menu.order-details.post', ['merchant' => $merchant->url_slug, 'order' => $order->url_slug]) }}"
                        method="POST" class="form">
                        @csrf
                        <input id="merchant_id" type="hidden" value="{{$merchant->url_slug}}">
                        <input id="order_id" type="hidden" value="{{$order->url_slug}}">
                        <div class="field field--split @error('customer_name') input-error @enderror">
                            <label for="customer_name">Name <abbr title="required">*</abbr></label>
                            <input required type="text" name="customer_name" id="customer_name" tabindex="1"
                                   value="{{ old('customer_name') }}"/>
                            @error('customer_name')
                            <p class="form__validation-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="field field--split @error('customer_name') input-error @enderror">
                            <label for="customer_email">Email <abbr title="required">*</abbr></label>
                            <input required type="email" name="customer_email" id="customer_email" tabindex="2"
                                   value="{{ old('customer_email') }}"/>
                            @error('customer_email')
                            <p class="form__validation-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="field field--split @error('customer_address') input-error @enderror">
                            <label for="customer_address">Address <abbr title="required">*</abbr></label>
                            <textarea required name="customer_address" id="customer_address"
                                      tabindex="3">{{ old('customer_address') }}</textarea>
                            @error('customer_address')
                            <p class="form__validation-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="field field--split @error('customer_phone') input-error @enderror">
                            <label for="customer_phone">Phone <abbr title="required">*</abbr></label>
                            <input type="tel" required  name="customer_phone" id="customer_phone" tabindex="4"
                                   value="{{ old('customer_phone') }}"/>
                            @error('customer_phone')
                            <p class="form__validation-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="field field--textarea">
                            <label for="customer_note">Add any special instructions or restrictions for the restaurant,
                                e.g. allergies or intolerances.</label>
                            <textarea type="text" name="customer_note" id="customer_note"
                                      tabindex="5">{{ old('customer_note', $order->customer_note) }}</textarea>
                            @error('customer_note')
                            <p class="form__validation-error">{{ $message }}</p>
                            @enderror
                        </div>
                        @if ($merchant->hasStripePaymentsIntegration())
                            <x-stripe-payment
                                :order="$order"
                                :merchant="$merchant"
                                :stripeMerchantAccountId="$stripeMerchantAccountId"
                                :stripeConnectAccountId="$stripeConnectAccountId"
                            />
                        @endif
                        <div class="field field--button">
                            <input type="hidden" name="order_no" value="{{ $order->url_slug }}"/>
                            <button type="submit" @if ($intentSecret !== null) id="submit_button" data-secret="{{ $intentSecret }}" @endif class="button button--icon-right button--filled button--filled-carnation">
                                <span class="button__content">Confirm order details</span>
                                <span class="icon icon-right">@svg('arrow-right')</span>
                            </button>
                        </div>
                    </form>
                </div>

                <div
                    class="col--lg-12-4 col--lg-offset-12-9 col--m-12-5 col--m-offset-12-8 col--sm-6-6 col--sm-offset-6-1 order">
                    <header class="order__header">
                        <h3 class="header header--five">Your Order</h3>
                    </header>
                    @if (!$order)
                        <div class="order__empty">
                            <p class="font--kepler-med">Your order is empty</p>
                        </div>
                    @endif
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
                                <p class="order__total-no-delivery"><span>Total:</span>
                                    £{{ $order->getFormattedUKPriceAttribute($order->total_cost) }}</p>
                                <p class="order__total-delivery"><span>Delivery Cost:</span>
                                    &pound;{{ $merchant->getFormattedUKPriceAttribute($merchant->delivery_cost) }}</p>
                                <p class="order__total-with-delivery"><span>Total:</span>
                                    £{{ $order->getFormattedUKPriceAttribute($order->total_cost, $merchant->delivery_cost) }}</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
