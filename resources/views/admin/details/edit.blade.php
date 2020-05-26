@extends('global.admin')
@section('content')
    <header class="dashboard__header col col--lg-12-10 col--sm-6-6 admin-inner-grid">
        <div class="dashboard__title col col--lg-12-6 col--sm-6-6">
            <h1 class="header header--three color--carnation">{{ $merchant->name }} details</h1>
        </div>
        <div class="dashboard__intro col col--lg-12-5 col--sm-6-5">
            <p>Edit your business details</p>
        </div>
    </header>
    <section class="registration registration--user-details col col--lg-12-10 col--sm-6-6 admin-inner-grid">
        <form
            class="col col--lg-12-6 col--lg-offset-12-3 col--m-12-8 col--m-offset-12-2 col--sm-6-6 col--sm-offset-6-1 form form--background"
            id="categoryForm"
            name="categoryForm"
            autocomplete="off"
            action="{{ route('admin.details.edit.post') }}"
            method="POST"
            enctype="multipart/form-data">
            @csrf

            <div class="field field--upload col col--lg-12-2 col--m-12-3 col-sm-6-3">
                <input type="file" name="logo" id="logo" class="upload-input" />
                <label for="logo">
                    <span class="icon icon--upload">@svg('upload')</span>
                    <span class="upload-input-name">Click to upload your business logo</span>
                </label>
            </div>
            @if ($merchant->logo !== null)
                <div class="field field--uploaded-file col col--lg-12-2 col--m-12-3 col-sm-6-3 spacer-bottom--30">
                    <img src="{{ $merchant->getTemporaryLogoLink() }}" alt="{{ $merchant->name }}" />
                    <p><i>Current Uploaded Logo</i></p>
                </div>
            @endif

            <div class="field @error('description') input-error @enderror col col--lg-12-6 col--m-12-8 col-sm-6-6">
                <br />
                <label for="description">Description</label>
                <textarea id="description" name="description">{{ old('description', $merchant->description) }}</textarea>
                @error('description')
                <p class="form__validation-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="field @error('contact_number') input-error @enderror col col--lg-12-6 col--m-12-8 col-sm-6-6">
                <label for="contact_number">Contact Number</label>
                <input type="text"
                       name="customer-phone-number"
                       id="contact_number"
                       tabindex="4"
                       value="{{ old('customer-phone-number', $merchant->contact_number) }}" />
                @error('contact_number')
                <p class="form__validation-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="field field--wrapper col col--lg-12-6 col--m-12-8 col-sm-6-6">
                <header class="section-title">
                    <p>How will customers receive their orders <abbr title="required">*</abbr></p>
                </header>
                <div class="field field--radio">
                    <input type="radio" name="collection_type"  data-collection-type="collection" class="collection--type"  id="allow-collection"  @if (old('collection_type') === 'collection' || ($merchant->allow_delivery === 0 && $merchant->allow_collection === 1)) checked="checked" @endif value="collection">
                    <label for="allow-collection">Collection</label>
                </div>
                <div class="field field--radio">
                    <input type="radio" name="collection_type" data-collection-type="delivery" class="collection--type" tabindex="7" @if (old('collection_type') === 'delivery' || ($merchant->allow_delivery === 1 && $merchant->allow_collection === 0)) checked="checked" @endif id="allow-delivery" value="delivery">
                    <label for="allow-delivery">Delivery</label>
                </div>
                <div class="field field--radio">
                    <input type="radio" name="collection_type" data-collection-type="delivery" class="collection--type" id="both"  @if (old('collection_type') === 'both' || ($merchant->allow_collection === 1 && $merchant->allow_delivery === 1)) checked="checked" @endif value="both">
                    <label for="both">Delivery & Collection</label>
                </div>
                @error('collection_type')
                <p class="form__validation-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="@if ($merchant->allow_delivery === 1) show @endif field field--price delivery col col--lg-12-6 col--m-12-8 col-sm-6-6 @error('delivery_cost') input-error @enderror">
                <label for="delivery_cost">If delivery is chosen, what is the customer delivery charge (can be £0)</label>
                <input type="text"
                       name="delivery_cost"
                       id="delivery_cost"
                       tabindex="4"
                       value="{{ old('delivery_cost', $merchant->getFormattedUKPriceAttribute($merchant->delivery_cost)) }}" />
                @error('delivery_cost')
                <p class="form__validation-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="@if ($merchant->allow_delivery === 1) show @endif field delivery col col--lg-12-6 col--m-12-8 col-sm-6-6 @error('delivery_radius') input-error @enderror">
                <label for="delivery_radius">Delivery radius in miles</label>
                <input type="text"
                       name="delivery_radius"
                       id="delivery_radius"
                       tabindex="4"
                       placeholder="Delivery radius in miles"
                       value="{{ old('delivery_radius', $merchant->delivery_radius) }}" />
                @error('name')
                <p class="form__validation-error">{{ $message }}</p>
                @enderror
            </div>
            <header class="section-title col col--lg-12-6 col--m-12-8 col-sm-6-6">
                <h3 class="header header--five color--carnation spacer-bottom--30">Connect Payment Platform</h3>
            </header>
            <x-stripe-payment-integration :merchant="$merchant" />

            <div class="field field--button">
                <button type="submit" class="button button--icon-right button--filled button--filled-carnation button--end">
                    <span class="button__content">Save</span>
                    <span class="icon icon-right">@svg('arrow-right')</span>
                </button>
            </div>
        </form>
    </section>
@endsection
