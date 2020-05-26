<p>Thank you {{$merchant->name}} for setting up your account and welcome to Awe-der!</p>
<p>All of the settings for your business and your orders can be accessed and amended at anytime at <a href="{{ route('admin.dashboard') }}">Dashboard</a>.</p>
<p>Your individual ordering page is now live at {{ route('store.menu.view', ['merchant' => $merchant->url_slug]) }}, This is the URL that you should give to your customers, add to your website and share via social media.</p>
<p>We hope that your Awe-der page helps you grow your business. If you have any feedback (good or bad) then we would love to hear it at <a href="mailto:hello@awe-der.net">hello@awe-der.net.</a></p>
<p>The Awe-der team</p>
