@extends('global.app')
@section('content')
    <section class="merchant-banner">
        <div class="row">
            <div class="content">
                <div class="merchant-banner__content col col--lg-12-6 col--lg-offset-12-2 col--m-12-8 col--m-offset-12-2 col--sm-6-6 col--sm-offset-6-1">
                    <header class="">
                        <h1 class="header header--three color--carnation spacer-bottom--10">{{ $merchant->name }}</h1>
                    </header>
                    <x-merchant-opening-hours :opening-hours="$merchant->openingHours" />
                    <div class="merchant-banner__details">
                        @if ($merchant->doesMerchantAllowDeliveryAndCollection())
                            <div class="merchant-banner__delivery">
                                <p class="merchant-banner__radius">
                                    <span class="icon icon--pin">@svg('pinpoint')</span>
                                    We deliver within a {{ $merchant->delivery_radius }} mile radius
                                </p>
                                <p class="merchant-banner__type">
                                    <span class="icon icon--delivery">@svg('bike')</span>
                                    Delivery & Collection
                                </p>
                                <p class="merchant-banner__type">
                                    <span class="icon icon--delivery">@svg('arrow-right')</span>
                                    Delivery Charge: £{{ $merchant->getFormattedUKPriceAttribute($merchant->delivery_cost) }}
                                </p>
                            </div>
                        @elseif ($merchant->deliveryOnly())
                            <div class="merchant-banner__delivery">
                                <p class="merchant-banner__radius">
                                    <span class="icon icon--pin">@svg('pinpoint')</span>
                                    We delivery within a {{ $merchant->delivery_radius }} mile radius
                                </p>
                                <p class="merchant-banner__type">
                                    <span class="icon icon--delivery">@svg('bike')</span>
                                    Delivery
                                </p>
                            </div>
                        @endif

                        <p>{{ $merchant->address }}</p>
                        <p class="merchant-banner__tel"><a href="tel:{{ $merchant->contact_number }}">{{ $merchant->contact_number }}</a></p>
                    </div>
                    @if ($merchant->description  !== null)
                        <div class="merchant-banner__description">
                            <p>{{ $merchant->description }}</p>
                        </div>
                    @endif
                </div>
                @if ($merchant->logo !== null)
                    <div class="merchant-banner__logo col col--lg-12-3 col--lg-offset-12-9 col--m-12-3 col--m-offset-12-10 col--sm-6-4 col--sm-offset-6-2 col--s-6-6 col--s-offset-6-1">
                        <img src="{{ $merchant->getTemporaryLogoLink() }}" alt="{{ $merchant->name }}">
                    </div>
                @endif
            </div>
        </div>
    </section>
    <section class="ordering">
        <div class="row row--menu">
            <div class="content">
                @if (!$merchant->categories->isEmpty() && $merchant->categories->count() > 1)
                    <nav class="menu__navigation col col--lg-12-12 col--sm-6-6">
                        <ul class="col col--lg-12-6 col--lg-offset-12-2 col--m-12-7 col--m-offset-12-2 col--sm-6-6 col--sm-offset-6-1">
                            @foreach ($merchant->categories as $category)
                                @if (!$category->inventoriesAvailable->isEmpty())
                                    <li><a href="#{{$category->title}}">{{ $category->title }}</a></li>
                                @endif
                            @endforeach
                        </ul>
                        <div class="ordering__status col col--lg-12-3 col--lg-offset-12-9 col--m-12-4 col--m-offset-12-9 col--sm-6-6 col--sm-offset-6-1">
                            @if (isset($order))
                                @if (!$order->items->isEmpty())
                                    <span class="icon icon--logo">@svg('aweder-logo-small')</span>
                                    <p class="ordering__status-items">Add more items - £{{ $order->getFormattedUKPriceAttribute($order->total_cost) }}</p>
                                @endif
                            @endif
                        </div>
                    </nav>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="content">
                <div class="col--lg-12-6 col--lg-offset-12-2 col--sm-6-6 col--sm-offset-6-1 menu">
                    @if (!$merchant->categories->isEmpty())
                        @foreach ($merchant->categories as $category)
                            @if (!$category->inventoriesAvailable->isEmpty())
                                <div class="menu__item" id="{{$category->title}}">
                                    <header class="menu__item-header">
                                        <h4 class="header header--five color--cloudburst spacer-bottom--50">{{ $category->title }}</h4>
                                    </header>
                                    <div class="menu__section">
                                        @foreach ($category->inventoriesAvailable as $inventory)
                                            <x-display-item :item="$inventory" :editable="$editable" :merchant="$merchant" :order="$order ?? null" />
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>

                <div class="col--lg-12-4 col--lg-offset-12-9 col--m-12-4 col--m-offset-12-9 col--sm-6-6 col--sm-offset-6-1 order">
                    @if (isset($order))
                        @if (!$order->items->isEmpty())
                            @foreach ($order->items as $item)
                                <div class="order__menu">
                                    <dl class="order__menu-list">
                                        <dd class="order__title">{{ $item->inventory->title }}</dd>
                                        <dd class="order__price">{{ $item->quantity }} * &pound;{{ $item->getFormattedUKPriceAttribute($item->price) }}</dd>
                                    </dl>
                                    @if ($editable === true)
                                        <form method="POST" action="{{ route('store.menu.remove-item', ['merchant' => $merchant->url_slug, 'order' => $order->url_slug]) }}" class="form form--order-remove">
                                            @csrf
                                            <input type="hidden" name="item" value="{{ $item->inventory->id }}" />
                                            @if (isset($order))
                                                <input type="hidden" name="order_no" value="{{ $order->url_slug }}" />
                                            @endif
                                            <div class="field field--button">
                                                <button type="submit" class="button button--icon button--outline button--remove">
                                                    <span class="icon icon--remove">
                                                       @svg('remove')
                                                   </span>
                                                </button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            @endforeach

                                <div class="order__total">
                                    <p class="order__total-no-delivery"><span>Total:</span>
                                        £{{ $order->getFormattedUKPriceAttribute($order->total_cost) }}</p>
                                    <p class="order__total-delivery"><span>Delivery Cost:</span>
                                        &pound;{{ $merchant->getFormattedUKPriceAttribute($merchant->delivery_cost) }}</p>
                                    <p class="order__total-with-delivery"><span>Total:</span>
                                        £{{ $order->getFormattedUKPriceAttribute($order->total_cost, $merchant->delivery_cost) }}</p>
                                </div>
                            <div class="order__completion">
                                @if ($editable === true)
                                <form method="POST" action="{{ route('store.order.submit', ['merchant' => $merchant->url_slug, 'order' => $order->url_slug]) }}" class="form form--delivery">
                                    @csrf
                                    <input type="hidden" name="order_no" value="{{ old('order_no', $order->url_slug) }}" />
                                    <div class="field">
                                        <label  for="note">Add any special instructions or restrictions for the restaurant, e.g. allergies or intolerances.</label>
                                        <textarea id="note" name="customer_note">{{ old('customer_note') }}</textarea>
                                    </div>
                                    @if ($merchant->doesMerchantAllowDeliveryAndCollection())
                                        <div class="field field--wrapper field--wrapper-radio">
                                            <div class="field field--radio field--radio-small">
                                                <input type="radio" class="collection-choice" data-collection-type="delivery"
                                                       name="collection_type"
                                                       @if (old('collection_type') === 'delivery') checked="checked"
                                                       @endif id="allow-delivery" value="delivery">
                                                <label for="allow-delivery">Delivery</label>
                                            </div>
                                            <div class="field field--radio field--radio-small">
                                                <input type="radio" class="collection-choice" data-collection-type="collection"
                                                       name="collection_type" id="allow-collection"
                                                       @if (old('collection_type') === 'collection') checked="checked"
                                                       @endif value="collection">
                                                <label for="allow-collection">Collection</label>
                                            </div>
                                            @error('collection_type')
                                            <span class="form__validation-error">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        @if ($merchant->doesMerchantAllowDeliveryAndCollection())
                                            <div class="field delivery--wrapper">
                                                <p>Delivery within a {{ $merchant->delivery_radius }} mile radius</p>
                                            </div>
                                        @endif
                                    @elseif ($merchant->deliveryOnly())
                                        <input type="hidden" name="collection_type" id="allow-collection"
                                               @if (old('collection_type') === 'delivery') checked="checked"
                                               @endif value="delivery">
                                    @else
                                        <input type="hidden" name="collection_type" id="allow-collection"
                                               @if (old('collection_type') === 'collection') checked="checked"
                                               @endif value="collection">
                                    @endif
                                    @error('collection_type')
                                    <p class="form__validation-error">{{ $message }}</p>
                                    @enderror

                                    <div class="field">
                                        @error('order_time')
                                        <span class="form__validation-error">{{ $message }}</span>
                                        @enderror
                                        <span class="label">Requested Order Time</span>
                                        <div class="select-wrapper">
                                            <div class="field field--select">
                                                <select name="order_time[hour]" class="select">
                                                    @for ($i = 0; $i <= 23; $i++)
                                                        <option value="{{ $i < 10 ? '0' . $i : $i }}"
                                                                @if (old('order_time.hour') == $i) selected @endif>{{ $i < 10 ? '0'
                                                . $i
                                                : $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <span>:</span>
                                            <div class="field field--select">
                                                <select name="order_time[minute]" class="select">
                                                    @for ($i = 0; $i < 12; $i++)
                                                        <option value="{{ $i * 5 < 10 ? '0' . $i * 5 : $i * 5}}"
                                                                @if (old('order_time.minute') == $i * 5) selected @endif>{{ $i * 5 <
                                                10
                                                ? '0' . $i * 5 : $i * 5 }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="field field--button">
                                        <button type="submit" class="button button--icon-right button--filled button--filled-carnation ">
                                            <span class="button__content">Place order</span>
                                            <span class="icon icon-right">@svg('arrow-right')</span>
                                        </button>
                                    </div>
                                </form>
                                    @endif
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
