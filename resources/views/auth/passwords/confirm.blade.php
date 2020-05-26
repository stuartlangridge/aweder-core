@extends('global.app')

@section('content')
    <section class="banner">
        <div class="row">
            <div class="content">
                <header class="col col--lg-12-6 col--lg-offset-12-2 col--l-12-6 col--l-offset-12-1 col--m-12-11 col--m-offset-12-1 col--sm-6-6 col--sm-offset-6-1 col--s-6-6">
                    <h1 class="header header--three color--carnation spacer-bottom--40">Reset your password</h1>
                </header>
                <div class="banner__copy col col--lg-12-6 col--lg-offset-12-2 col--l-12-6 col--l-offset-12-1 col--m-12-11 col--m-offset-12-1 col--sm-6-6 col--sm-offset-6-1 col--s-6-6">
                    <p>Please confirm your password before continuing</p>
                </div>
            </div>
        </div>
    </section>

    <section class="">
        <div class="row">
            <div class="content">
                <div class="login__form col col--lg-12-6 col--lg-offset-12-4 col--l-12-6 col--l-offset-12-4 col--m-12-11 col--m-offset-12-1 col--sm-6-6 col--sm-offset-6-1 col--s-6-6">
                    <form
                        class="form form--background"
                        id="signUpForm"
                        name="signUpForm"
                        autocomplete="off"
                        action="{{ route('login') }}"
                        method="POST">
                        @csrf
                        <div class="field @error('password') input-error @enderror">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password"/>
                            @error('password')
                            <p class="form__validation-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="field">
                            <label for="remember">Remember Me</label>
                            <input type="checkbox" name="remember" id="remember">
                        </div>
                        <br/><br/>
                        <div class="field field--button">
                            <button type="submit" class="button button--icon-right button--filled button--filled-carnation button--end">
                                <span class="button__content">Confirm password</span>
                                <span class="icon icon-right">@svg('arrow-right')</span>
                            </button>
                        </div>
                    </form>
                    @if (Route::has('password.request'))
                        <div class="forgotten-password">
                            <a href="{{ route('password.request') }}">Forgot Your Password?</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
