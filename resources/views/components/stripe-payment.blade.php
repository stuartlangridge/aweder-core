<div class="payment payment--stripe">
    <input type="hidden" id="stripe_connect_account_id"  value="{{ $stripeConnectAccountId }}" />
    <input type="hidden" id="stripe_merchant_account_id"  value="{{ $stripeMerchantAccountId }}" />
    <input type="hidden" name="intent_id" value="" id="stripe_payment_intent_id" />
    <div class="field">
        <label for="card-element">Credit or debit card</label>
        <div id="card-element"></div>
        <div id="card-errors" class="form__validation-error" role="alert"></div>
    </div>
</div>
