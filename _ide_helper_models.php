<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\Category
 *
 * @property int $id
 * @property int $merchant_id
 * @property int $category_id
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Inventory[] $inventories
 * @property-read int|null $inventories_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereMerchantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Inventory[] $inventoriesAvailable
 * @property-read int|null $inventories_available_count
 */
	class Category extends \Eloquent {}
}

namespace App{
/**
 * App\Order
 *
 * @property int $id
 * @property string $url_slug
 * @property int $merchant_id
 * @property string|null $status
 * @property int $is_delivery
 * @property string|null $customer_name
 * @property string|null $customer_email
 * @property string|null $customer_address
 * @property string|null $customer_phone
 * @property string|null $available_time
 * @property string|null $rejection_reason
 * @property int|null $total_cost
 * @property int|null $final_cost
 * @property string|null $merchant_note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read float $formatted_u_k_price
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OrderItem[] $items
 * @property-read int|null $items_count
 * @property-read \App\Merchant $merchant
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereAvailableTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCustomerAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCustomerEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCustomerPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereFinalCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereIsDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereMerchantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereMerchantNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereRejectionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereTotalCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereUrlSlug($value)
 * @mixin \Eloquent
 * @property string|null $customer_note
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCustomerNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order status($value)
 * @property string|null $order_submitted
 * @property string|null $customer_requested_time
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCustomerRequestedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereOrderSubmitted($value)
 * @property int $order_id
 * @property int|null $reminder_time This is the minute reminder time
 * which should allow us to stop duplication being sent
 * @property string|null $sent
 * @property-read \App\Order $order
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderReminder whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderReminder whereReminderTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderReminder whereSent($value)
 */
	class OrderReminder extends \Eloquent {}
}

namespace App{
/**
 * App\Inventory
 *
 * @property int $id
 * @property int $merchant_id
 * @property int $category_id
 * @property string $title
 * @property string|null $description
 * @property int $price
 * @property int $available
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read float $formatted_u_k_price
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Inventory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Inventory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Inventory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Inventory whereAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Inventory whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Inventory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Inventory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Inventory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Inventory whereMerchantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Inventory wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Inventory whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Inventory whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OrderItem[] $orderItems
 * @property-read int|null $order_items_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Inventory onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Inventory whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Inventory withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Inventory withoutTrashed()
 */
	class Inventory extends \Eloquent {}
}

namespace App{
/**
 * App\Order
 *
 * @property int $id
 * @property string $url_slug
 * @property int $merchant_id
 * @property string|null $status
 * @property int $is_delivery
 * @property string|null $customer_name
 * @property string|null $customer_email
 * @property string|null $customer_address
 * @property string|null $customer_phone
 * @property string|null $available_time
 * @property string|null $rejection_reason
 * @property int|null $total_cost
 * @property int|null $final_cost
 * @property string|null $merchant_note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read float $formatted_u_k_price
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OrderItem[] $items
 * @property-read int|null $items_count
 * @property-read \App\Merchant $merchant
 * @method static Builder|\App\Order newModelQuery()
 * @method static Builder|\App\Order newQuery()
 * @method static Builder|\App\Order query()
 * @method static Builder|\App\Order whereAvailableTime($value)
 * @method static Builder|\App\Order whereCreatedAt($value)
 * @method static Builder|\App\Order whereCustomerAddress($value)
 * @method static Builder|\App\Order whereCustomerEmail($value)
 * @method static Builder|\App\Order whereCustomerName($value)
 * @method static Builder|\App\Order whereCustomerPhone($value)
 * @method static Builder|\App\Order whereFinalCost($value)
 * @method static Builder|\App\Order whereId($value)
 * @method static Builder|\App\Order whereIsDelivery($value)
 * @method static Builder|\App\Order whereMerchantId($value)
 * @method static Builder|\App\Order whereMerchantNote($value)
 * @method static Builder|\App\Order whereRejectionReason($value)
 * @method static Builder|\App\Order whereStatus($value)
 * @method static Builder|\App\Order whereTotalCost($value)
 * @method static Builder|\App\Order whereUpdatedAt($value)
 * @method static Builder|\App\Order whereUrlSlug($value)
 * @mixin \Eloquent
 * @property string|null $customer_note
 * @method static Builder|\App\Order whereCustomerNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order status($value)
 * @property string|null $order_submitted
 * @property string|null $customer_requested_time
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCustomerRequestedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereOrderSubmitted($value)
 * @property int $delivery_cost
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OrderReminder[] $reminders
 * @property-read int|null $reminders_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereDeliveryCost($value)
 * @property string|null $payment_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order wherePaymentId($value)
 */
	class Order extends \Eloquent {}
}

namespace App{
/**
 * App\NormalOpeningHour
 *
 * @property int $id
 * @property int $merchant_id
 * @property int $day_of_week
 * @property string $open_time
 * @property string $close_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $merchant
 * @method static \Illuminate\Database\Eloquent\Builder|\App\NormalOpeningHour newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\NormalOpeningHour newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\NormalOpeningHour query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\NormalOpeningHour whereCloseTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\NormalOpeningHour whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\NormalOpeningHour whereDayOfWeek($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\NormalOpeningHour whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\NormalOpeningHour whereMerchantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\NormalOpeningHour whereOpenTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\NormalOpeningHour whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class NormalOpeningHour extends \Eloquent {}
}

namespace App{
/**
 * App\OrderItem
 *
 * @property int $id
 * @property int $order_id
 * @property int $quantity
 * @property int $price The price is in pence
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $inventory_id
 * @property-read \App\Inventory $inventory
 * @property-read \App\Order $order
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereInventoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Inventory $orderInventory
 * @property-read string $formatted_u_k_price
 */
	class OrderItem extends \Eloquent {}
}

namespace App{
/**
 * Class User
 *
 * @package App
 * @property int $id
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Merchant[] $merchants
 * @property-read int|null $merchants_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|
 * \Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|
 * \Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|
 * \Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|
 * \Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|
 * \Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|
 * \Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|
 * \Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 */
	class User extends \Eloquent {}
}

namespace App{
/**
 * App\Merchant
 *
 * @property int $id
 * @property string $url_slug
 * @property string $name
 * @property string|null $contact_email
 * @property string $contact_number
 * @property string $address
 * @property string|null $mobile_number the mobile number for SMS notifications
 * @property string|null $notification_method the method to use for notifications
 * @property string|null $customer_phone_number the telephone number to display to customers
 * @property int|null $allow_delivery whether the merchant allows delivery
 * @property int|null $allow_collection whether the merchant allows collection
 * @property string|null $address_name_number the building name or number for the address
 * @property string|null $address_street the street for the address
 * @property string|null $address_city the city for the address
 * @property string|null $address_county the county for the address
 * @property string|null $address_postcode the postcode for the address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Category[] $categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Inventory[] $inventories
 * @property-read int|null $inventories_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Merchant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Merchant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Merchant query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Merchant whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Merchant whereAddressCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Merchant whereAddressCounty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Merchant whereAddressLocality($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Merchant whereAddressNameNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Merchant whereAddressPostcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Merchant whereAddressStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Merchant whereAllowCollection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Merchant whereAllowDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Merchant whereContactEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Merchant whereContactNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Merchant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Merchant whereCustomerPhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Merchant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Merchant whereMobileNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Merchant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Merchant whereNotificationMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Merchant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Merchant whereUrlSlug($value)
 * @mixin \Eloquent
 * @property string|null $salt
 * @property int|null $registration_stage
 * @property string|null $description
 * @property string|null $logo
 * @property int $delivery_cost
 * @property int $delivery_radius
 * @property-read string $formatted_u_k_price
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\NormalOpeningHour[] $openingHours
 * @property-read int|null $opening_hours_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Provider[] $paymentProviders
 * @property-read int|null $payment_providers_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Merchant whereDeliveryCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Merchant whereDeliveryRadius($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Merchant whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Merchant whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Merchant whereRegistrationStage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Merchant whereSalt($value)
 */
	class Merchant extends \Eloquent {}
}

namespace App{
/**
 * App\Tracker
 *
 * @property int $id
 * @property string $subject_id id of the model
 * @property string $subject_type The model used
 * @property string|null $user_id
 * @property string $name name of the model and event
 * @property mixed $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $subject
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tracker newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tracker newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tracker query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tracker whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tracker whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tracker whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tracker whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tracker whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tracker whereSubjectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tracker whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tracker whereUserId($value)
 * @mixin \Eloquent
 */
	class Tracker extends \Eloquent {}
}

namespace App{
/**
 * App\Provider
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Merchant[] $merchants
 * @property-read int|null $merchants_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Provider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Provider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Provider query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Provider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Provider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Provider whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Provider whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Provider extends \Eloquent {}
}

