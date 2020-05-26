<div class="field col col--lg-12-6 col--m-12-8 col-sm-6-6">
    @if (!$merchant->hasStripePaymentsIntegration())
        <a href="https://connect.stripe.com/oauth/authorize?client_id={{ $stripeClientId }}&state={{ $status }}&scope=read_write&response_type=code&stripe_user[email]={{$merchant->contact_email}}&redirect_uri={{ route('admin.stripe-oauth.redirect') }}" class="stripe-connect"><span>Connect with Stripe</span></a>
    @else
        <a href="{{ route('admin.stripe-oauth.deauthorize') }}" class="button button--icon-right button--filled button--filled-carnation">
            <span class="button__content">Deauthorize Stripe Account</span>
            <span class="icon icon-right">@svg('arrow-right')</span>
        </a>
    @endif
</div>
