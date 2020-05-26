<div class="menu__list">
    @if ($item->image)
        <span>
            <img src="{{ $item->getTemporaryInventoryImageLink() }}">
        </span>
    @endif
    <div class="menu__description">
        <p class="menu__title font--gibson-semi color--cloudburst">{{ $item->title }} @if ($item->description !== null && !empty($item->description)) - <span>{{ $item->description }}</span>@endif</p>
        <p class="menu__price">£{{ $item->getFormattedUKPriceAttribute($item->price) }}</p>
        @if ($editable === true)
            <div class="menu__add">
                <form method="POST" action="{{ route('store.order.add', [$merchant->url_slug]) }}">
                    @csrf
                    <input type="hidden" name="item" value="{{ $item->id }}" />
                    @if (isset($order))
                        <input type="hidden" name="order_no" value="{{ $order->url_slug }}" />
                    @endif
                    <button type="submit" class="menu__addto color--carnation">Add &plus;</button>
                </form>
            </div>
        @endif
    </div>
</div>
