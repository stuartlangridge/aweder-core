<nav class="admin-nav col col--lg-12-2 col--l-12-3">
    <a href="{{ route('admin.dashboard')}}" class="admin-branding">
        <span class="icon icon--logo">
            @svg('aweder-logo')
        </span>
    </a>
    <ul class="admin-menu">
        <li class="admin-menu__item">
            <a href="{{ route('admin.dashboard') }}" class="admin-menu__link">
                <span class="icon"></span>Merchant dashboard
            </a>
        </li>
        <li class="admin-menu__item">
            <a href="{{ route('admin.orders.view-all') }}" class="admin-menu__link">
                <span class="icon"></span>Orders
            </a>
        </li>
        <li class="admin-menu__item">
            <a href="{{ route('admin.inventory') }}" class="admin-menu__link">
                <span class="icon"></span>Inventory
            </a>
        </li>
        <li class="admin-menu__item">
            <a href="{{ route('admin.categories') }}" class="admin-menu__link">
                <span class="icon"></span>Categories
            </a>
        </li>
        <li class="admin-menu__item">
            <a href="{{ route('admin.opening-hours') }}" class="admin-menu__link">
                <span class="icon"></span>Opening hours
            </a>
        </li>
        <li class="admin-menu__item">
            <a href="{{ route('admin.details.edit') }}" class="admin-menu__link">
                <span class="icon"></span>Business Details
            </a>
        </li>
        <li class="admin-menu__item">
            <a href="{{ route('logout') }}" class="admin-menu__link">
                <span class="icon"></span> Logout
            </a>
        </li>
    </ul>
</nav>