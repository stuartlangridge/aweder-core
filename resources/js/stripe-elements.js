const StripeElements = class StripeElements {
  /**
   *
   * @type Stripe
   */
  stripe = null;

  /**
   *
   * @type string
   */
  clientSecret = null;

  /**
   *
   * @type {null}
   */
  card = null;

  /**
   *
   * @type Element
   */
  form = null;

  /**
   *
   * @type {null}
   */
  intentInput = null;

  /**
   *
   * @type {null}
   */
  submitButton = null;

  setupElement = (element) => {
    const style = {
      base: {
        padding: '0 0 10px 0',
        color: '#32325d',
        fontFamily: 'canada-type-gibson,sans-serif',
        fontSmoothing: 'antialiased',
        borderBottom: '1px solid #243752',
        fontSize: '16px',
        '::placeholder': {
          color: 'rgba(36,55,82,0.6)',
        },
      },
      invalid: {
        color: '#fa755a',
        iconColor: '#fa755a',
      },
    };

    this.card = element.create('card', { style });

    this.card.mount('#card-element');

    this.listenForErrors(this.card);

    this.form = document.getElementsByTagName('form')[0];

    this.form.addEventListener('submit', this.formSubmission);
  };

  formSubmission = (ev) => {
    ev.preventDefault();
    this.submitButton.disabled = true;

    this.stripe.confirmCardPayment(this.clientSecret, {
      payment_method: {
        card: this.card,
      },
    }).then((result) => {
      if (result.error) {
        this.showErrorMessage(result.error.message);
        this.submitButton.disabled = false;
        // Show error to your customer (e.g., insufficient funds)
      } else {
        // The payment has been processed!
        if (result.paymentIntent.status === 'requires_capture') {
          this.setIntentInputFormValue(result.paymentIntent);
          this.form.removeEventListener('submit', this.formSubmission);
          this.form.submit();
        }
      }
    });
  }

  listenForErrors = (card) => {
    card.addEventListener('change', (event) => {
      const displayError = document.getElementById('card-errors');
      if (event.error) {
        displayError.textContent = event.error.message;
      } else {
        displayError.textContent = '';
      }
    });
  };

  showErrorMessage = (errorMessage) => {
    alert(errorMessage);
  };

  setIntentInputFormValue = (paymentIntent) => {
    this.intentInput.value = paymentIntent.id;
  };

  init = () => {
    const key = document.getElementById('stripe_connect_account_id').value;

    const stripeMerchantAccount = document.getElementById('stripe_merchant_account_id').value;

    const submitButton = document.getElementById('submit_button');

    this.intentInput = document.getElementById('stripe_payment_intent_id');

    this.clientSecret = submitButton.dataset.secret;

    this.submitButton = document.getElementById('submit_button');

    this.stripe = Stripe(key, {
      stripeAccount: stripeMerchantAccount,
    });
    if (this.stripe !== null && this.stripe !== undefined) {
      const element = this.stripe.elements();

      if (element !== null) {
        this.setupElement(element, this.stripe, this.clientSecret);
      }
    }
  }
};

export default StripeElements;
