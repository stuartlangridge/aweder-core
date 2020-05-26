@extends('global.admin')
@section('content')
    <header class="dashboard__header col col--lg-12-10 col--sm-6-6 admin-inner-grid">
        <div class="dashboard__title col col--lg-12-6 col--sm-6-6">
            <h1 class="header header--three color--carnation">Orders</h1>
        </div>
        <div class="dashboard__intro col col--lg-12-5 col--sm-6-5">
            <p>View all accepted orders below</p>
        </div>
    </header>
    <section class="dashboard--outstanding-orders col col--lg-12-10 col--sm-6-6 admin-inner-grid">
        @if (!$outstanding->isEmpty())
            <header class="outstanding-orders__header col col--lg-12-10 col--sm-6-6">
                <h3 class="header header--five color--carnation spacer-bottom--30">Waiting</h3>
            </header>
            <div
                class="outstanding-orders__listing spacer-bottom--30 col col--lg-12-10 col--sm-6-6">
                @foreach($outstanding as $order)
                    <x-order-card-component :order="$order" />
                @endforeach
            </div>
            {{$outstanding->appends(['outstanding' => $outstanding->currentPage(), 'accepted' => $accepted->currentPage(), 'rejected' => $rejected->currentPage(), 'fulfilled' => $fulfilled->currentPage()])->links()}}
        @endif
        @if (!$accepted->isEmpty())
            <header class="outstanding-orders__header col col--lg-12-10 col--sm-6-6">
                <h3 class="header header--five color--carnation spacer-bottom--30">Not Yet Fulfilled</h3>
            </header>
            <div
                class="outstanding-orders__listing spacer-bottom--30 col col--lg-12-10 col--sm-6-6">
                @foreach($accepted as $order)
                    <x-order-card-component :order="$order" />
                @endforeach
                {{$accepted->appends(['outstanding' => $outstanding->currentPage(), 'accepted' => $accepted->currentPage(), 'rejected' => $rejected->currentPage(), 'fulfilled' => $fulfilled->currentPage()])->links()}}

            </div>
        @endif
        @if (!$rejected->isEmpty())
        <header class="outstanding-orders__header col col--lg-12-10 col--sm-6-6">
            <h3 class="header header--five color--carnation spacer-bottom--30">Rejected Orders</h3>
        </header>
        <div
            class="outstanding-orders__listing spacer-bottom--30 col col--lg-12-10 col--sm-6-6">

            @foreach($rejected as $order)
                <x-order-card-component :order="$order" />
            @endforeach
            {{$rejected->appends(['outstanding' => $outstanding->currentPage(), 'accepted' => $accepted->currentPage(), 'rejected' => $rejected->currentPage(), 'fulfilled' => $fulfilled->currentPage()])->links()}}
        </div>
        @endif

            @if (!$fulfilled->isEmpty())
                <header class="outstanding-orders__header col col--lg-12-10 col--sm-6-6">
                    <h3 class="header header--five color--carnation spacer-bottom--30">Fulfilled Orders</h3>
                </header>
                <div
                    class="outstanding-orders__listing spacer-bottom--30 col col--lg-12-10 col--sm-6-6">
                    @foreach($fulfilled as $order)
                        <x-order-card-component :order="$order" />
                    @endforeach
                    {{$rejected->appends(['outstanding' => $outstanding->currentPage(), 'accepted' => $accepted->currentPage(), 'rejected' => $rejected->currentPage(), 'fulfilled' => $fulfilled->currentPage()])->links()}}
                </div>
            @endif
    </section>
@endsection
