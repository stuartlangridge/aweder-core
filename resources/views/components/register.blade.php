<section class="register">
    <div class="row">
        <div class="content">
            <div class="col--lg-12-5 col--sm-6-6 register__copy">
                <p>In this time of uncertainty during the Covid-19 outbreak, we want to help as many small businesses such as Restaurants, Pubs, Cafes and Retailers survive. Pivoting your business to a takeaway or remote service model can be daunting and in the next few days we will launch the Aweder platform to make this as simple and seamless as possible without incurring unnecessary expense.</p>
                <ul>
                    <li>Menu / Product listing</li>
                    <li>Customer order processing</li>
                    <li>Online payment integration (coming soon)</li>
                    <li>Order acceptance and pickup timing confirmation</li>
                    <li>Instant on-boarding and setup for businesses</li>
                </ul>
                <div class="register__button">
                    <a href="{{ route('about.how-it-works') }}" class="button button--outline">
                        <span class="button__content">How it works</span>
                    </a>
                </div>
            </div>
            <div class="register__form col col--lg-12-6 col--lg-offset-12-7 col--sm-6-6 col--sm-offset-6-1">
                <header class="register__header">
                    <h4 class="header header--four">Register your interest</h4>
                </header>
                @include('shared.register-interest')
            </div>
        </div>
    </div>
</section>
