@extends('global.app')
@section('content')
    <section class="banner">
        <div class="row">
            <div class="content">
                <header class="banner__header col col--lg-12-6 col--lg-offset-12-2 col--l-12-6 col--l-offset-12-1 col--m-12-11 col--m-offset-12-1 col--sm-6-6 col--sm-offset-6-1 col--s-6-6">
                    <h2 class="header header--three color--carnation">Quickly move your business to accepting online orders for pickup or delivery.</h2>
                </header>
                <div class="banner__copy col col--lg-12-4 col--lg-offset-12-2 col--l-12-6 col--l-offset-12-1 col--m-12-11 col--m-offset-12-1  col--sm-6-6 col--sm-offset-6-1 col--s-6-6">
                    <p>Simple online order taking for small businesses with payment acceptance and pickup / delivery confirmation.</p>
                </div>
                <div class="banner__svg col col--lg-12-3 col--lg-offset-12-9 col--l-12-4 col--l-offset-12-8 col--m-12-4 col--m-offset-12-5 col--sm-6-4 col--sm-offset-6-2 col--s-6-4 col--s-offset-6-2">
                    @svg('logo-mark')
                </div>
            </div>
        </div>
    </section>
    <section class="how-it-works">
        <div class="row">
            <div class="content">
                <div class="how-it-works__content col col--lg-12-4 col--lg-offset-12-7 col--l-12-6 col--l-offset-12-7 col--sm-6-6 col--sm-offset-6-1 col--s-6-6">
                    <header class="section-title spacer-bottom--40">
                        <h2 class="header header--three color--carnation">Simple and seamless</h2>
                    </header>
                    <div class="spacer-bottom--40">
                        <p>In this time of uncertainty during the Covid-19 outbreak, we want to help as many small businesses such as Restaurants, Pubs, Cafes and Retailers survive. Pivoting your business to a takeaway or remote service model can be daunting the Awe-der platform is here to make this as simple and seamless as possible without incurring unnecessary expense.</p>
                    </div>
                    <a href="{{route('about.how-it-works')}}" class="button button--outline button--outline-red">
                        <span class="button__content">How it works</span>
                    </a>
                </div>
                <div class="how-it-works__image col col--lg-12-4 col--lg-offset-12-2 col--l-12-6 col--l-offset-12-1 col--sm-6-4 col--sm-offset-6-2 col--s-6-6 col--s-offset-6-1">
                    <img src="images/image1.png" alt="awe-der menu" />
                </div>
            </div>
        </div>
    </section>
    <section class="here-to-help">
        <div class="row">
            <div class="content">
                <div class="here-to-help__content col col--lg-12-4 col--lg-offset-12-2 col--l-12-6 col--l-offset-12-1 col--sm-6-6 col--sm-offset-6-1 col--s-6-6 col--s-offset-6-1">
                    <header class="section-title spacer-bottom--40">
                        <h2 class="header header--three color--carnation">We&acute;re here to help you</h2>
                    </header>
                    <div class="spacer-bottom--40">
                        <p>At Awe-der we want to get you up and running online as soon as possible, allowing your business to focus on supporting the community in these uncertain times. Here are some of the features we can offer:</p>
                        <ul>
                            <li class="spacer-bottom--40">
                                <strong class="spacer-bottom--10">Menu / Product listing</strong>
                                Quickly add your products with descriptions and prices.
                            </li>
                            <li class="spacer-bottom--40">
                                <strong class="spacer-bottom--10">Order acceptance and pickup timing confirmation</strong>
                                View and accept incoming orders from your dashboard.
                            </li>
                            <li class="spacer-bottom--40">
                                <strong class="spacer-bottom--10">Online payment integration (coming soon)</strong>
                                Support for Stripe and other payment providers to quickly and securely take online payments
                            </li>
                            <li class="spacer-bottom--40">
                                <strong class="spacer-bottom--10">Instant on-boarding and setup for businesses</strong>
                                Setup your online store with ease and start selling!
                            </li>
                        </ul>
                    </div>
                    <a href="{{route('register')}}" class="button button--filled button--filled-carnation">
                        <span class="button__content">Get started with Awe-der</span>
                    </a>
                </div>
                <div class="here-to-help__image col col--lg-12-4 col--lg-offset-12-7 col--l-12-6 col--l-offset-12-7 col--sm-6-4 col--sm-offset-6-2 col--s-6-6 col--s-offset-6-1">
                    <img src="images/image2.png" alt="orders" />
                </div>
            </div>
        </div>
    </section>
@endsection
