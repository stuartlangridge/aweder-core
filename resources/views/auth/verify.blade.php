@extends('global.app')

@section('content')
    <section class="intro">
        <div class="row">
            <div class="content">
                <header class="intro__header col col--lg-12-8 col--sm-6-6 col--s-6-6">
                    <h1 class="header header--three color--carnation">Verify your email address.</h1>
                </header>
                @if (session('resent'))
                    <div class="intro__copy col col--lg-12-6 col--sm-6-6 col--s-6-6">
                        <p>A Fresh verification link has been sent to your email address</p>
                        <p><a href="{{ route('about.how-it-works') }}">Find out more about how Aweder will work</a></p>
                    </div>
                @endif
            </div>
        </div>
    </section>
    <div class="content">
        <div class="login__form col col--lg-12-6 col--lg-offset-12-4 col--l-12-6 col--l-offset-12-4 col--m-12-11 col--m-offset-12-1 col--sm-6-6 col--sm-offset-6-1 col--s-6-6">
            <p>Before proceeding, please check your email for a verification link</p>
            <p>If you did not receive the email, use the form below</p>
            <form
                class="form form--background"
                id="resend-verification"
                name="resendVerification"
                autocomplete="off"
                method="POST"
                action="{{ route('verification.resend') }}">
                @csrf
                <div class="field field--button">
                    <button type="submit" class="button button--icon-right button--filled button--filled-carnation button--end">
                        <span class="button__content">Sign in</span>
                        <span class="icon icon-right">@svg('arrow-right')</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
