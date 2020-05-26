@extends('global.admin')
@section('content')
    <header class="dashboard__header col col--lg-12-10 col--sm-6-6 admin-inner-grid">
        <div class="dashboard__title col col--lg-12-6 col--sm-6-6">
            <h1 class="header header--three color--carnation">Dashboard</h1>
        </div>
        <div class="dashboard__intro col col--lg-12-5 col--sm-6-5">
            @if ($showSignUpDashboardMessage === true)
            <p>Welcome to Awe-der, setup is complete and your order page is now live at <a href="{{ route('store.menu.view', $merchant->url_slug) }}">{{ route('store.menu.view', $merchant->url_slug) }}</a>.</p>
            <p>This is the dashboard where you can view and process all orders as well as change any settings</p>
            @else
            <p>Your merchant order page is <a target="_blank" href="{{ route('store.menu.view', $merchant->url_slug) }}">{{ route('store.menu.view', $merchant->url_slug) }}</a></p>
            @endif
        </div>
    </header>
    <section class="dashboard__content">
        <x-dashboard-order-filtering-component :period="$period" :filteredStatus="$statusFilter"/>
        <div class="col col--lg-12-10 dashboard__metrics">
            <div class="metric">
                <p>New Orders</p> <span class="metric__count">{{ $dashboardMetrics['New Order'] }}</span>
            </div>
            <div class="metric">
                <p>Processing</p> <span class="metric__count">{{ $dashboardMetrics['Processing'] ?? 0 }}</span>
            </div>
            <div class="metric">
                <p>Completed</p> <span class="metric__count">{{ $dashboardMetrics['Completed'] ?? 0 }}</span>
            </div>
            <div class="metric">
                <p>Rejected</p> <span class="metric__count">{{ $dashboardMetrics['Rejected'] ?? 0 }}</span>
            </div>
        </div>
        @if (!$orders->isEmpty())
        <div class="col col--lg-12-10 col--sm-6-6 dashboard__filtering">
            <div class="dashboard__filter">
                <form action={{ route('admin.dashboard') }} method="GET" id="dashboard-status-sort">
                    <select name="sort" id="dashboard-status-filter-select">
                        <option value="">Sort</option>
                        <option value="asc"
                        @if ($sort === 'asc')
                            selected
                        @endif
                        >Ascending</option>
                        <option value="desc"
                        @if ($sort === 'desc')
                            selected
                        @endif
                        >Descending</option>
                    </select>
                    <span class="icon icon--filter">@svg('sort')</span>
                </form>
            </div>
            <div class="dashboard__filter">
                <form action={{ route('admin.dashboard') }} method="GET" id="dashboard-status-filter">
                    <select name="status" id="dashboard-status-filter-select">
                        <option value="">Filter orders</option>
                        @foreach ($filterOptions as $option => $label)
                            <option value={{ $option }}
                            @if ($option === $statusFilter)
                                    selected
                                    @endif
                            >{{ $label }}</option>
                        @endforeach
                    </select>
                    <span class="icon icon--filter">@svg('filter')</span>
                </form>
            </div>
            <x-dashboard-search-component/>
        </div>
        <div class="col col--lg-12-10 col--sm-6-6 dashboard__table">
            <table class="dashboard__table">
                <thead>
                    <tr>
                        <th>Ordered</th>
                        <th>Order #</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Customer</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                    <tr>
                        <td>{{$order->order_submitted->format('d M, H:i')}}</td>
                        <td><a href="{{ route('admin.view-order', $order->url_slug) }}">{{ $order->url_slug }}</a></td>
                        <td>{{$order->getIsDeliveryOrCollection()}}</td>
                        <td><a href="{{ route('admin.view-order', $order->url_slug) }}">{{ $order->getNiceFrontendStatus() }}</a></td>
                        <td><a href="{{ route('admin.view-order', $order->url_slug) }}">{{ $order->customer_name }}</a></td>
                        <td>
                        @if ($order->is_delivery === 1)
                            &pound;{{$order->getFormattedUKPriceAttribute($order->total_cost, $order->delivery_cost)}}
                        @else
                            &pound;{{$order->getFormattedUKPriceAttribute($order->total_cost)}}
                        @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </section>
@endsection
