<nav class="site-nav col col--lg-12-9 col--sm-6-2 col--sm-offset-6-5">
    <input type="checkbox" id="mobile-menu-trigger" />
    <label class="site-nav__burger" for="mobile-menu-trigger"></label>
    <ul class="site-menu site-menu--header">
        <li class="site-menu__item">
            <a href="{{ route('about.how-it-works') }}" class="site-menu__link">
                How it works
            </a>
        </li>
        <li class="site-menu__item">
            <a href="{{ route('login') }}" class="site-menu__link button button--outline-cream">
                <span class="button__content">Merchant login</span>
            </a>
        </li>
        <li class="site-menu__item">
            <a href="{{ route('register') }}" class="site-menu__link button button--filled-cream">
                <span class="button__content">Merchant sign up</span>
            </a>
        </li>
    </ul>
</nav>
