<form
    id="signUpForm"
    class="for form--background"
    name="signUpForm"
    autocomplete="off"
    action="{{ route('register.interest') }}"
    method="POST">
    @csrf
    <div class="field @error('business') input-error @enderror">
        <label for="business">Business Name</label>
        <input type="text" name="business" id="business" tabindex="1"  value="{{ old('business') }}" placeholder="Business name" />
        @error('business')
            <p class="form__validation-error">{{ $message }}</p>
        @enderror
    </div>
    <div class="field @error('location') input-error @enderror">
        <label for="location">Location</label>
        <input type="text" name="location" id="location" tabindex="2"  value="{{ old('location') }}" placeholder="Location" />
        @error('location')
            <p class="form__validation-error">{{ $message }}</p>
        @enderror
    </div>
    <div class="field @error('email') input-error @enderror">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" tabindex="3"  value="{{ old('email') }}" placeholder="Email" />
        @error('email')
            <p class="form__validation-error">{{ $message }}</p>
        @enderror
    </div>
    <div class="field field--select field--icon @error('business_type') input-error @enderror">
        <label for="business_type">Business Type</label>
        <select name="business_type" id="business_type" tabindex="4" >
            <option value="" disabled selected>Business Type - Please Choose</option>
            <option value="restaurant">Restaurant</option>
            <option value="pub">Pub</option>
            <option value="cage">Cafe</option>
            <option value="retailer">Retailer</option>
            <option value="other">Other</option>
        </select>
        <span class="field__icon field__icon--select"></span>
        @error('business_type')
            <p class="form__validation-error">{{ $message }}</p>
        @enderror
    </div>
    <div class="field field--button">
        <button type="submit" class="button button--green">
            <span class="button__content">Register interest</span>
        </button>
    </div>
</form>
