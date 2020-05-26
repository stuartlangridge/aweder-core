@extends('global.app')

@section('content')
    <section class="intro">
        <div class="row">
            <div class="content">
                <header class="intro__header col col--lg-12-8 col--sm-6-6 col--s-6-6">
                    <h1 class="header header--three">Register to launch<br /> Awe-der.</h1>
                </header>
                <div class="intro__copy col col--lg-12-8 col--sm-6-6 col--s-6-6">
                    <p>To get started with Awe-der, please fill in your user and business details below. This is used as your business contact details for customers.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="register">
        <div class="row">
            <div class="content">
                <form
                    class="form form--sections col col--lg-12-12 col--sm-6-6"
                    id="signUpForm"
                    name="signUpForm"
                    autocomplete="off"
                    action="{{ route('register.manage') }}"
                    method="POST">
                    @csrf
                    <div class="form__section form--background col col--lg-12-5 col--lg-offset-12-1 col--l-12-5 col--l-offset-12-1 col--m-12-6 col--m-offset-12-1 col--sm-6-5 col--sm-offset-6-1 col--s-6-6 col--s-offset-6-1">
                        <header class="section-title">
                            <h3 class="header header--five">Your user details</h3>
                        </header>
                        <div class="field @error('email') input-error @enderror">
                            <label for="email">Email <abbr title="required">*</abbr></label>
                            <input type="email" name="email" id="email" tabindex="1"  value="{{ old('email') }}" placeholder="Email" />
                            @error('email')
                            <p class="form__validation-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="field @error('password') input-error @enderror">
                            <label for="password">Password <abbr title="required">*</abbr></label>
                            <input type="password" name="password" tabindex="2"  id="password" placeholder="Password" />
                            @error('password')
                            <p class="form__validation-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="field @error('password-confirmed') input-error @enderror">
                            <label for="password-confirmed">Confirm Password <abbr title="required">*</abbr></label>
                            <input type="password" name="password-confirmed" tabindex="3"  id="password-confirmed" placeholder="Confirm password" />
                            @error('password-confirmed')
                            <p class="form__validation-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="form__section form--background col col--lg-12-5 col--lg-offset-12-6 col--l-12-5 col--l-offset-12-6 col--m-12-6 col--m-offset-12-7 col--sm-6-5 col--sm-offset-6-1 col--s-6-6 col--s-offset-6-1">
                        <header class="section-title">
                            <h3 class="header header--five">Your business details</h3>
                        </header>
                        <div class="field @error('name') input-error @enderror">
                            <label for="name">Your business name <abbr title="required">*</abbr></label>
                            <input type="text" name="name" id="name" tabindex="4"  value="{{ old('name') }}" />
                            @error('name')
                            <p class="form__validation-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="field @error('url-slug') input-error @enderror">
                            <label for="url-slug">The business's URL slug <abbr title="required">*</abbr></label>
                            <input type="text" name="url-slug" id="url-slug" tabindex="5"  value="{{ old('url-slug') }}" />
                            @error('url-slug')
                            <p class="form__validation-error">{{ $message }}</p>
                            @enderror
                            <p class="form__note">This will generate your url - for example - if you enter red-lion you will have https://aweder.net/red-lion</p>
                        </div>
                        <div class="field field--wrapper">
                            <header class="section-title col col--lg-12-5">
                                <h3 class="header header--five">How will customers receive their orders <abbr title="required">*</abbr></h3>
                            </header>
                            <div class="field field--radio">
                                <input type="radio" name="collection_type" data-collection-type="delivery" class="collection--type" tabindex="6" @if (old('collection_type') === 'delivery') checked="checked" @endif id="allow-delivery" value="delivery">
                                <label for="allow-delivery">Delivery only</label>
                            </div>
                            <div class="field field--radio">
                                <input type="radio" name="collection_type" data-collection-type="collection" class="collection--type"  id="allow-collection"  @if (old('collection_type') === 'collection') checked="checked" @endif value="collection">
                                <label for="allow-collection">Collection only</label>
                            </div>
                            <div class="field field--radio">
                                <input type="radio" name="collection_type" data-collection-type="delivery" class="collection--type" id="both"  @if (old('collection_type') === 'both') checked="checked" @endif value="both">
                                <label for="both">Both Delivery and Collection</label>
                            </div>
                            @error('collection_type')
                            <p class="form__validation-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="delivery--wrapper">
                            <div class="field @error('delivery_cost') input-error @enderror">
                                <label for="name">Deliver Cost &pound;</label>
                                <input type="text"
                                       name="delivery_cost"
                                       id="delivery_cost"
                                       tabindex="4"
                                       value="{{ old('delivery_cost') }}" />
                                @error('delivery_cost')
                                <p class="form__validation-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="field @error('delivery_radius') input-error @enderror">
                                <label for="name">Delivery Radius in miles</label>
                                <input type="text"
                                       name="delivery_radius"
                                       id="delivery_radius"
                                       tabindex="4"
                                       value="{{ old('delivery_radius') }}" />
                                @error('name')
                                <p class="form__validation-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form__section form--background col col--lg-12-5 col--lg-offset-12-6 col--l-12-5 col--l-offset-12-6 col--m-12-6 col--m-offset-12-7 col--sm-6-5 col--sm-offset-6-1 col--s-6-6 col--s-offset-6-1">
                        <header class="section-title">
                            <h3 class="header header--five">Your business address</h3>
                        </header>
                        <div class="field @error('address-name-number') input-error @enderror">
                            <label for="address-name-number">The building number or name <abbr title="required">*</abbr></label>
                            <input type="text" name="address-name-number" tabindex="9"   id="address-name-number" value="{{ old('address-name-number') }}" />
                            @error('address-name-number')
                            <p class="form__validation-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="field @error('address-street') input-error @enderror">
                            <label for="address-street">The street <abbr title="required">*</abbr></label>
                            <input type="text" name="address-street" tabindex="10"  id="address-street" value="{{ old('address-street') }}" />
                            @error('address-street')
                            <p class="form__validation-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="field @error('address-locality') input-error @enderror">
                            <label for="address-locality">The address locality <abbr title="required">*</abbr></label>
                            <input type="text" name="address-locality" tabindex="11" id="address-locality" value="{{ old('address-locality') }}" />
                            @error('address-locality')
                            <p class="form__validation-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="field @error('address-city') input-error @enderror">
                            <label for="address-city">The address city <abbr title="required">*</abbr></label>
                            <input type="text" name="address-city" tabindex="12" id="address-city" value="{{ old('address-city') }}" />
                            @error('address-city')
                            <p class="form__validation-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="field @error('address-county') input-error @enderror">
                            <label for="address-county">The address county <abbr title="required">*</abbr></label>
                            <input type="text" name="address-county" tabindex="13" id="address-county" value="{{ old('address-county') }}" />
                            @error('address-county')
                            <p class="form__validation-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="field @error('address-postcode') input-error @enderror">
                            <label for="address-postcode">The address postcode <abbr title="required">*</abbr></label>
                            <input type="text" name="address-postcode" tabindex="14" id="address-postcode" value="{{ old('address-postcode') }}" />
                            @error('address-postcode')
                            <p class="form__validation-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="field field--button">
                            <button type="submit" class="button button--green">
                                <span class="button__content">Sign up</span>
                            </button>
                        </div>
                    </div>
                    <div class="form__section form--background col col--lg-12-5 col--lg-offset-12-1 col--l-12-5 col--l-offset-12-1 col--m-12-6 col--m-offset-12-1 col--sm-6-5 col--sm-offset-6-1 col--s-6-6 col--s-offset-6-1">
                        <header class="section-title">
                            <h3 class="header header--five">Business Contact Numbers</h3>
                        </header>
                        <div class="field @error('mobile-number') input-error @enderror">
                            <label for="mobile-number">A mobile telephone number for SMS notifications (this remains private) <abbr title="required">*</abbr></label>
                            <input type="tel" pattern="[0-9]{11}" name="mobile-number" tabindex="7" id="mobile-number" value="{{ old('mobile-number') }}" />
                            @error('mobile-number')
                            <p class="form__validation-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="field @error('customer-phone-number') input-error @enderror">
                            <label for="customer-phone-number">The telephone number that customers can contact you on (this is shown to customers) <abbr title="required">*</abbr></label>
                            <input type="tel" pattern="[0-9]{11}" name="customer-phone-number" tabindex="8" id="customer-phone-number" value="{{ old('customer-phone-number') }}" />
                            @error('customer-phone-number')
                            <p class="form__validation-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <?php /**
                    <div class="form__section form--background col col--lg-12-5 col--lg-offset-12-1 col--l-12-5 col--l-offset-12-1 col--m-12-6 col--m-offset-12-1 col--sm-6-5 col--sm-offset-6-1 col--s-6-6 col--s-offset-6-1">

                        <header class="section-title">
                            <h3 class="header header--five">Your Stripe API key</h3>
                        </header>
                        <p>Stripe is used to process payments for you.  We will need your publishable API key.</p>
                        <div class="field @error('api-key') input-error @enderror">
                            <label for="api-key">Your Stripe API Key</label>
                            <input type="password" name="api-key" id="api-key" />
                            @error('api-key')
                            <p class="form__validation-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="">
                            <a target="_blank" href="https://dashboard.stripe.com/register">Not Registered with Stripe?</a>
                        </div>
                    </div>
                    **/ ?>
                </form>
            </div>
        </div>
    </section>
@endsection
