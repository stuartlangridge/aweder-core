@extends('global.app')
@section('content')
    <section class="registration registration--user-details">
        <div class="row">
            <div class="content">
                <x-registration-steps :stage="'contact-details'"/>
                <form
                    class="col--lg-12-6 col--lg-offset-12-4 col--m-12-8 col--m-offset-12-3 col--sm-6-6 col--sm-offset-6-1"
                    id="signUpForm"
                    name="signUpForm"
                    autocomplete="off"
                    action="{{ route('register.contact-details.post') }}"
                    method="POST">
                    @csrf
                    <p class="col col--lg-12-6 col--m-12-8 col-sm-6-6">Please enter your contact numbers below:</p>
                    <div class="field col col--lg-12-6 col--m-12-8 col-sm-6-6 @error('mobile-number') input-error @enderror">
                        <label for="mobile-number">OUR MOBILE TELEPHONE NUMBER, THIS REMAINS PRIVATE AND MAY BE USED IN THE FUTURE FOR NOTIFICATIONS) <abbr
                                title="required">*</abbr></label>
                        <input type="tel"
                                name="mobile-number" tabindex="7" id="mobile-number"
                               value="{{ old('mobile-number') }}" placeholder="Mobile number (private)" />
                        @error('mobile-number')
                        <p class="form__validation-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="field col col--lg-12-6 col--m-12-8 col-sm-6-6 @error('customer-phone-number') input-error @enderror">
                        <label for="customer-phone-number">Phone number customers can contact you on (this is shown to customers)<abbr title="required">*</abbr></label>
                        <input type="tel"  name="customer-phone-number" tabindex="8"
                               id="customer-phone-number"
                               value="{{ old('customer-phone-number') }}"
                               placeholder="Contact number (public)" />
                        @error('customer-phone-number')
                        <p class="form__validation-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="field col col--lg-12-6 col--m-12-8 col-sm-6-6 field--button">
                        <button type="submit" class="button button--icon-right button--filled button--filled-carnation button--end">
                            <span class="button__content">Next</span>
                            <span class="icon icon-right">@svg('arrow-right')</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
