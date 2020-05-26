@extends('global.app')
@section('content')
    <section class="registration registration--user-details">
        <div class="row">
            <div class="content">
                <x-registration-steps :stage="'business-details'"/>
                <form
                    class="col--lg-12-6 col--lg-offset-12-4 col--m-12-8 col--m-offset-12-3 col--sm-6-6 col--sm-offset-6-1"
                    id="signUpForm"
                    name="signUpForm"
                    autocomplete="off"
                    action="{{ route('register.business-details.post') }}"
                    method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <p class="col col--lg-12-6 col--m-12-8 col-sm-6-6">Please enter your business details below:</p>
                    <div class="field field--upload col col--lg-12-2 col--m-12-3 col-sm-6-3">
                        <input type="file" name="logo" id="logo" class="upload-input" />
                        <label for="logo">
                            <span class="icon icon--upload">@svg('upload')</span>
                            <span class="upload-input-name">Click to upload your business logo</span>
                        </label>
                    </div>
                    <div class="field col--lg-12-4 col--m-12-5 col-sm-6-6 @error('name') input-error @enderror">
                        <label for="name">Your business name <abbr title="required">*</abbr></label>
                        <input type="text" name="name" id="name" tabindex="4"  value="{{ old('name') }}" placeholder="Business name" />
                        @error('name')
                        <p class="form__validation-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="field col--lg-12-4 col--lg-offset-12-3 col--m-12-5 col--m-offset-12-4 col-sm-6-6 col--sm-offset-6-1 @error('url-slug') input-error @enderror">
                        <label for="url-slug">The business's URL slug <abbr title="required">*</abbr></label>
                        <input type="text" name="url-slug" id="url-slug" tabindex="5"  value="{{ old('url-slug') }}" placeholder="URL slug" />
                        @error('url-slug')
                        <p class="form__validation-error">{{ $message }}</p>
                        @enderror
                        <p class="form__note">This will generate your url - for example - if you enter red-lion you will have https://aweder.net/red-lion</p>
                    </div>
                    <div class="field field--wrapper col col--lg-12-6 col--m-12-8 col-sm-6-6">
                        <label for="description">Enter a description about your business and/or state any notes for your order form e.g. ‘This is Thursdays menu’ <abbr title="required">*</abbr></label>
                        <textarea name="description" tabindex="6" id="description">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="form__validation-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="field field--wrapper col col--lg-12-6 col--m-12-8 col-sm-6-6">
                        <header class="section-title">
                            <h3 class="header header--five color--carnation spacer-bottom--30">How will customers receive their orders <abbr title="required">*</abbr></h3>
                        </header>
                        <div class="field field--radio">
                            <input type="radio" name="collection_type" data-collection-type="collection" class="collection--type"  id="allow-collection"  @if (old('collection_type') === 'collection') checked="checked" @endif value="collection">
                            <label for="allow-collection">Collection</label>
                        </div>
                        <div class="field field--radio">
                            <input type="radio" name="collection_type" data-collection-type="delivery" class="collection--type" tabindex="7" @if (old('collection_type') === 'delivery') checked="checked" @endif id="allow-delivery" value="delivery">
                            <label for="allow-delivery">Delivery</label>
                        </div>
                        <div class="field field--radio">
                            <input type="radio" name="collection_type" data-collection-type="delivery" class="collection--type" id="both"  @if (old('collection_type') === 'both') checked="checked" @endif value="both">
                            <label for="both">Delivery & Collection</label>
                        </div>
                        @error('collection_type')
                        <p class="form__validation-error">{{ $message }}</p>
                        @enderror
                    </div>
                        <div class="field field--price delivery col col--lg-12-6 col--m-12-8 col-sm-6-6 @if(!$errors->isEmpty()) show @endif @error('delivery_cost') input-error @enderror">
                            <label for="name">If delivery is chosen, what is the customer delivery charge (can be £0)</label>
                            <input type="text"
                                   name="delivery_cost"
                                   id="delivery_cost"
                                   tabindex="4"
                                   value="{{ old('delivery_cost') }}" />
                            @error('delivery_cost')
                            <p class="form__validation-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="field delivery col col--lg-12-6 col--m-12-8 col-sm-6-6 @if(!$errors->isEmpty()) show @endif @error('delivery_cost') input-error @enderror">
                            <label for="name">Delivery radius in miles</label>
                            <input type="text"
                                   name="delivery_radius"
                                   id="delivery_radius"
                                   tabindex="4"
                                   placeholder="Delivery radius in miles"
                                   value="{{ old('delivery_radius') }}" />
                            @error('delivery_radius')
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
