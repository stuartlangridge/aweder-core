<div>
    @if ($order->status === 'acknowledged')
        <a href="{{ route('admin.order-fulfilled', $order->url_slug) }}"  class="button button--icon-right button--filled button--filled-carnation button--end">
            <span class="button__content">Mark as fulfilled</span>
            <span class="icon icon-right">@svg('arrow-right')</span>
        </a>
    @endif
</div>
