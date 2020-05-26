<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Home\\IndexController')->name('home');
Route::post('/register-interest', 'Store\\Interest\\RegisterController')->name('register.interest');

Route::get('/validate-slug/{slug}', function ($slug) {
    return json_encode(['exists' => \App\Merchant::whereUrlSlug($slug)->exists()]);
});

Route::get('/how-it-works', 'About\\HowItWorksController')->name('about.how-it-works');
Route::get('/thanks', 'Store\\Interest\\ThanksController')->name('register.thanks');

Route::middleware(['auth'])->namespace('Auth')
    ->prefix('registration')->group(function (Router $router) {
        $router->get('/opening-hours', ['uses' => 'Registration\\OpeningHours\\SetupController'])
            ->name('registration.opening-hours');
        $router->post('/opening-hours', ['uses' => 'Registration\\OpeningHours\\CreateController'])
            ->name('registration.opening-hours.post');

        $router->get('/categories', ['uses' => 'Registration\\Categories\\SetupController'])
            ->name('registration.categories');
        $router->post('/categories', ['uses' => 'Registration\\Categories\\CreateController'])
            ->name('registration.categories.post');
    });

Route::middleware(['auth', 'has-user-completed-registration-stage:0'])
    ->namespace('Admin')->prefix('admin')->group(function (Router $router) {
        $router->get('dashboard', ['uses' => 'Dashboard\\DashboardController'])
            ->middleware(['merchant-has-completed-setup'])
            ->name('admin.dashboard');

        $router->get('edit-details', ['uses' => 'Details\\EditDetailsController'])
            ->name('admin.details.edit');

        $router->post('edit-details', ['uses' => 'Details\\PostEditDetailsController'])
            ->name('admin.details.edit.post');

        $router->get('view-order/{order}', ['uses' => 'Order\\ViewOrderController'])
            ->middleware(['order-belongs-to-merchant'])
            ->name('admin.view-order');

        $router->post('view-order/{order}/reject', ['uses' => 'Order\\RejectOrderController'])
            ->middleware(['order-belongs-to-merchant', 'has-order-gone-past-stage:rejected'])
            ->name('admin.reject-order');

        $router->post('view-order/{order}/accept', ['uses' => 'Order\\AcceptOrderController'])
            ->middleware(['order-belongs-to-merchant', 'has-order-gone-past-stage:accepted'])
            ->name('admin.accept-order');

        $router->get('view-order/{order}/fulfilled', ['uses' => 'Order\\MarkOrderAsFulfilledController'])
            ->middleware(['order-belongs-to-merchant', 'has-order-gone-past-stage:fulfilled'])
            ->name('admin.order-fulfilled');

        $router->get('/opening-hours', ['uses' => 'OpeningHours\\SetupController'])
            ->name('admin.opening-hours');
        $router->post('/opening-hours', ['uses' => 'OpeningHours\\CreateController'])
            ->name('admin.opening-hours.post');
        $router->get('/categories', ['uses' => 'Categories\\SetupController'])
            ->name('admin.categories');
        $router->post('/categories', ['uses' => 'Categories\\CreateController'])
            ->name('admin.categories.post');

        $router->get('/orders', ['uses' => 'Order\\ViewAllOrdersController'])
            ->name('admin.orders.view-all');

        //manage inventory
        $router->get('/inventory', ['uses' => 'Inventory\\SetupController'])
            ->name('admin.inventory');
        $router->post('/inventory', ['uses' => 'Inventory\\CreateController'])
            ->name('admin.inventory.post');
        $router->get('/inventory/delete/{id}', ['uses' => 'Inventory\\DeleteController'])
            ->name('admin.inventory.delete');
        $router->get('/inventory/status/{id}', ['uses' => 'Inventory\\StatusController'])
            ->name('admin.inventory.status');
        $router->put('/inventory/{inventory}/update', ['uses' => 'Inventory\\UpdateController'])
            ->name('admin.inventory.update');

        $router->get(
            '/payment/stripe/oauth',
            [
                'uses' => 'Payment\\Stripe\\OAuthRedirectController',
            ]
        )->name('admin.stripe-oauth.redirect');

        $router->get(
            '/payment/stripe/deauthorize',
            [
                'uses' => 'Payment\\Stripe\\DeauthorizeController',
            ]
        )->name('admin.stripe-oauth.deauthorize');
    });

Route::prefix('register')->namespace('Auth\\Registration\\MultiStep')
    ->group(function (Router $router) {
        $router->namespace('UserDetails')->group(function (Router $router) {
            $router->get('/', ['uses' => 'UserDetails'])
                ->name('register');
            $router->post('/', ['uses' => 'UserDetailsPost'])
                ->name('register.user-details.post');
        });

        $router->namespace('BusinessDetails')
            ->middleware(['auth', 'has-user-completed-registration-stage:2'])
            ->prefix('business-details')
            ->group(function (Router $router) {
                $router->get('/', ['uses' => 'Details'])
                    ->name('register.business-details');
                $router->post('/', ['uses' => 'DetailsPost'])
                    ->name('register.business-details.post');
            });

        $router->namespace('ContactDetails')
            ->middleware(['auth', 'has-user-completed-registration-stage:3'])
            ->prefix('contact-details')
            ->group(function (Router $router) {
                $router->get('/', ['uses' => 'Details'])
                    ->name('register.contact-details');
                $router->post('/', ['uses' => 'DetailsPost'])
                    ->name('register.contact-details.post');
            });

        $router->namespace('BusinessAddress')
            ->middleware(['auth', 'has-user-completed-registration-stage:4'])
            ->prefix('business-address')
            ->group(function (Router $router) {
                $router->get('/', ['uses' => 'Details'])
                    ->name('register.business-address');
                $router->post('/', ['uses' => 'DetailsPost'])
                    ->name('register.business-address.post');
            });
    });

Auth::routes(['verify' => true, 'register' => false]);

Route::get('logout', 'Auth\\LoginController@logout')->name('logout');

//This handles the magic for the stores
Route::post('/{merchant}/add-to-order', 'Store\\Orders\\CreateController')
    ->name('store.order.add');
Route::post('/{merchant}/{order}/submit-order', 'Store\\Orders\\SubmitController')
    ->name('store.order.submit');

Route::get('/{merchant}/{order}/order-details', 'Store\\Orders\\OrderDetails\\OrderDetailsController')
    ->middleware(['order-belongs-to-merchant'])
    ->name('store.menu.order-details');

Route::post('/{merchant}/{order}/order-details', 'Store\\Orders\\OrderDetails\\OrderDetailsPostController')
    ->middleware(['order-belongs-to-merchant'])
    ->name('store.menu.order-details.post');

Route::get('/{merchant}/{order}/payment', 'Store\\Orders\\Payment\\PaymentController')
    ->name('store.menu.payment');

Route::post('/{merchant}/{order}/payment', 'Store\\Orders\\Payment\\PaymentPostController')
    ->name('store.menu.payment.post');

Route::post('/{merchant}/{order}/create-intent', 'Store\\Payments\\CreateController')
    ->name('store.payment.create');

Route::get('/{merchant}/{order}/thanks', 'Store\\Orders\\ThankYouController')
    ->name('store.menu.order-thank-you');

Route::post('/{merchant}/{order}/remove', 'Store\\Orders\\RemoveItemController')
    ->name('store.menu.remove-item');
Route::get('/{merchant}/{order?}', 'Store\\Menu\\ViewController')
    ->name('store.menu.view');
