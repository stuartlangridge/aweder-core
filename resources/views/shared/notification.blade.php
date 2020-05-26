@if (session()->has('error'))
<div class="notification notification--error">
    <div class="row">
        <div class="content">
            <div class="notification__message col col--lg-12-8 col--lg-offset-12-3 col--l-12-10 col--l-offset-12-2 col--sm-6-6 col--sm-offset-6-1">
                <p>{{ session()->get('error') }}</p>
                {{session()->forget('error')}}
            </div>
        </div>
    </div>
</div>
@endif
@if (session()->has('success'))
    <div class="notification notification--success">
        <div class="row">
            <div class="content">
                <div class="notification__message col col--lg-12-8 col--lg-offset-12-3 col--l-12-10 col--l-offset-12-2 col--sm-6-6 col--sm-offset-6-1">
                    <p>{{ session()->get('success') }}</p>
                    {{session()->forget('success')}}
                </div>
            </div>
        </div>
    </div>
@endif
