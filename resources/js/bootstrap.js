window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import 'core-js/stable';
import 'regenerator-runtime/runtime';
import NotificationBanner from './notification';
import SlugChecker from './slug-checker';
import Upload from './upload';
import MerchantRegistration from './merchant_registration';
import Delivery from './delivery_cost';
import StripeElements from './stripe-elements';
import OrderFilters from './order_filters';
import AdminMenu from './admin-menu';

import '@/sass/app.scss';

const banner = new NotificationBanner();
banner.init();

const slugChecker = new SlugChecker();
slugChecker.init();

const stripe = new StripeElements();
if (document.getElementById('card-element')) {
  stripe.init();
}

const merchantRegistration = new MerchantRegistration();
merchantRegistration.init();

const collectionChoice = new Delivery();
collectionChoice.init();

const upload = new Upload();
upload.init();

const orderFilters = new OrderFilters();
orderFilters.init();

const adminMenu = new AdminMenu();
adminMenu.init();
