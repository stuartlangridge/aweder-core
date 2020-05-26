<nav class="registration__nav col col--lg-12-8 col--lg-offset-12-3 col--m-12-10 col--m-offset-12-2 col--sm-6-6 col--sm-offset-6-1">
    <ul>
        <li @if ($stage === 'user-details') class="active" @endif>User Information</li>
        <li @if ($stage === 'business-details') class="active" @endif>Business Details</li>
        <li @if ($stage === 'contact-details') class="active" @endif>Contact Numbers</li>
        <li @if ($stage === 'business-address') class="active" @endif>Business Address</li>
        <?php /**<li @if ($stage === 'stripe-api') class="active" @endif>Stripe API</li>**/?>
    </ul>
</nav>
