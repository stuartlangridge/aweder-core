@extends('global.app')
@section('content')
    <section class="registration registration--user-details">
        <div class="row">
            <div class="content">
                <x-registration-steps :stage="'business-address'"/>
                <form
                    class="col--lg-12-6 col--lg-offset-12-4 col--m-12-8 col--m-offset-12-3 col--sm-6-6 col--sm-offset-6-1"
                    id="signUpForm"
                    name="signUpForm"
                    autocomplete="off"
                    action="{{ route('register.business-address.post') }}"
                    method="POST">
                    @csrf
                    <p class="col col--lg-12-6 col--m-12-8 col-sm-6-6">Please enter the business address where you are trading from, this is shown to customers:</p>
                    <div class="field col col--lg-12-6 col--m-12-8 col-sm-6-6 @error('address-name-number') input-error @enderror">
                        <label for="address-name-number">Building number or name <abbr title="required">*</abbr></label>
                        <input type="text" name="address-name-number" tabindex="9"   id="address-name-number" value="{{ old('address-name-number') }}" placeholder="Building number" />
                        @error('address-name-number')
                        <p class="form__validation-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="field col col--lg-12-6 col--m-12-8 col-sm-6-6 @error('address-street') input-error @enderror">
                        <label for="address-street">Street <abbr title="required">*</abbr></label>
                        <input type="text" name="address-street" tabindex="10"  id="address-street" value="{{ old('address-street') }}" placeholder="Street" />
                        @error('address-street')
                        <p class="form__validation-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="field col col--lg-12-6 col--m-12-8 col-sm-6-6 @error('address-city') input-error @enderror">
                        <label for="address-city">Town or Locality <abbr title="required">*</abbr></label>
                        <input type="text" name="address-city" tabindex="12" id="address-city" value="{{ old('address-city') }}" placeholder="City" />
                        @error('address-city')
                        <p class="form__validation-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="field col col--lg-12-4 col--m-12-8 col-sm-6-6 @error('address-county') input-error @enderror">
                        <label for="address-county">County <abbr title="required">*</abbr></label>
                        <input type="text" name="address-county" tabindex="13" id="address-county" value="{{ old('address-county') }}" placeholder="County" />
                        @error('address-county')
                        <p class="form__validation-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="field col col--lg-12-2 col--m-12-8 col-sm-6-6 @error('address-postcode') input-error @enderror">
                        <label for="address-postcode">Postcode <abbr title="required">*</abbr></label>
                        <input type="text" name="address-postcode" tabindex="14" id="address-postcode" value="{{ old('address-postcode') }}" placeholder="Postcode" />
                        @error('address-postcode')
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
