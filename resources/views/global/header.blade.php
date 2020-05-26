<header class="site-header">
    <div class="row">
        <div class="content">
            <div class="col col--lg-12-3 col--sm-6-2 site-logo">
                <a href="{{ route('home')}}" class="site-branding">
                      <span class="icon icon--logo">
                          @svg('aweder-logo')
                      </span>
                        <span class="icon icon--logo-mobile">
                        @svg('aweder-logo-small')
                      </span>
                </a>
            </div>
            @include('global/navigation')
        </div>
    </div>
</header>
