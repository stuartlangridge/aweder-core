<header class="admin-header col col--lg-12-10 col--lg-offset-12-3 col--l-12-9 col--l-offset-12-4 col--m-12-12 col--m-offset-12-1 col--sm-6-6 col--sm-offset-6-1">
    <div class="admin__greeting">
        @if ($merchant->logo !== null)
            <span class="avatar">
                <img src="{{ $merchant->getTemporaryLogoLink() }}" alt="{{ $merchant->name }}" />
            </span>
        @endif
        <p id="admin-mobile-trigger">Welcome {{ $merchant->name }}!
            <span class="icon icon--down">@svg('arrow-down')</span>
        </p>
        <ul class="admin-mobile-nav" id="admin-mobile-nav">
            <li class="admin-mobile__item">
                <a href="{{ route('admin.dashboard') }}" class="admin-mobile__link">Merchant dashboard</a>
            </li>
            <li class="admin-mobile__item">
                <a href="{{ route('admin.orders.view-all') }}" class="admin-mobile__link">Orders</a>
            </li>
            <li class="admin-mobile__item">
                <a href="{{ route('admin.inventory') }}" class="admin-mobile__link">Inventory</a>
            </li>
            <li class="admin-mobile__item">
                <a href="{{ route('admin.categories') }}" class="admin-mobile__link">Categories</a>
            </li>
            <li class="admin-mobile__item">
                <a href="{{ route('admin.opening-hours') }}" class="admin-mobile__link">Opening hours</a>
            </li>
            <li class="admin-mobile__item">
                <a href="{{ route('admin.details.edit') }}" class="admin-mobile__link">Business details</a>
            </li>
            <li class="admin-mobile__item">
                <a href="{{ route('logout') }}" class="admin-mobile__link">Logout</a>
            </li>
        </ul>
    </div>
</header>