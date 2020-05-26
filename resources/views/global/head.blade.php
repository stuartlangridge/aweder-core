<head>
    @if (app()->environment() !== 'local')
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-54306199-18"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-54306199-18');
    </script>
    @endif
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="content-language" content="en-gb">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ url()->to('/') }}/images/aweder-ico.png">
    <title>Awe-der - Quickly move your business to accepting online orders for pickup or delivery</title>
    <meta property="og:site_name" content="Awe-der">
    <meta property="og:title" content="Awe-der">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ url()->to('/') }}/images/share.jpg">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:description"
          content="Quickly move your business to accepting online orders for pickup or delivery">
    <meta property="twitter:card" content="summary_large_image">
    <meta name="twitter:image:alt"
          content="Awe-der - Quickly move your business to accepting online orders for pickup or delivery">
    <meta name="description" content="Quickly move your business to accepting online orders for pickup or delivery">
    <meta name="keywords" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ url()->to('/') }}/style.css?{{ time() }}">
    @if (url()->current() === route('admin.dashboard') && app()->environment() !== 'local')
        <meta http-equiv="refresh" content="30">
    @endif
    <script>document.documentElement.className = document.documentElement.className.replace('no-js', 'js');</script>
    @if (isset($showStripeJs))
        <script src="https://js.stripe.com/v3/"></script>
    @endif
</head>
