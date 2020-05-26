<?php

namespace App\Repository;

use App\Contract\Repositories\MerchantContract;
use App\Traits\HelperTrait;
use App\Merchant;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Psr\Log\LoggerInterface;

class MerchantRepository implements MerchantContract
{
    use HelperTrait;

    protected Merchant $model;

    protected LoggerInterface $logger;

    public function __construct(Merchant $model, LoggerInterface $logger)
    {
        $this->model = $model;

        $this->logger = $logger;
    }

    public function createMerchant(User $user, array $registrationData = []): ?Merchant
    {
        return $this->getModel()->create(
            [
                'salt' => \Str::random(10),
                'registration_stage' => $registrationData['registration_stage'] ?? 1,
                'url_slug' => $registrationData['url-slug'],
                'name' => $registrationData['name'],
                'description' => $registrationData['description'] ?? null,
                'contact_number' => $registrationData['customer-phone-number'] ?? null,
                'address' => $this->generateAddressFromRegistrationForm($registrationData, null),
                'mobile_number' => $registrationData['mobile-number'] ?? null,
                'customer_phone_number' => $registrationData['customer-phone-number'] ?? null,
                'allow_delivery' => $this->doesMerchantAllowDelivery($registrationData['collection_type']),
                'allow_collection' => $this->doesMerchantAllowCollection($registrationData['collection_type']),
                'address_name_number' => $registrationData['address-name-number'] ?? null,
                'address_street' => $registrationData['address-street'] ?? null,
                'address_postcode' => $registrationData['address-postcode'] ?? null,
                'address_city' => $registrationData['address-city'] ?? null,
                'address_county' => $registrationData['address-county'] ?? null,
                'contact_email' => $user->email,
                'delivery_radius' => $registrationData['delivery_radius'] ?? 0,
                'delivery_cost' => isset($registrationData['delivery_cost'])
                    ? $this->convertToPence($registrationData['delivery_cost']) : 0,
            ]
        );
    }

    public function updateMerchant(Merchant $merchant, array $registrationData = []): bool
    {
        return $merchant->update(
            [
                'description' => isset($registrationData['description']) ? $registrationData['description']
                    : $merchant->description,
                'registration_stage' => $registrationData['registration_stage'] ?? 1,
                'contact_number' => $registrationData['customer-phone-number'] ?? $merchant->contact_number,
                'address' => $this->generateAddressFromRegistrationForm($registrationData, $merchant),
                'mobile_number' => $registrationData['mobile-number'] ?? $merchant->mobile_number,
                'customer_phone_number' => $registrationData['customer-phone-number']
                    ?? $merchant->customer_phone_number,
                'address_name_number' => $registrationData['address-name-number'] ?? $merchant->address_name_number,
                'address_street' => $registrationData['address-street'] ?? $merchant->address_street,
                'address_postcode' => $registrationData['address-postcode'] ?? $merchant->address_postcode,
                'address_city' => $registrationData['address-city'] ?? $merchant->address_city,
                'address_county' => $registrationData['address-county'] ?? $merchant->address_county,
                'contact_email' => $merchant->users()->first()->email,
                'delivery_radius' => isset($registrationData['delivery_radius'])
                    ? $registrationData['delivery_radius']
                    : $merchant->delivery_radius,
                'delivery_cost' => isset($registrationData['delivery_cost'])
                    ? $this->convertToPence($registrationData['delivery_cost']) : $merchant->delivery_cost,
                'allow_delivery' => isset($registrationData['collection_type'])
                    ? $this->doesMerchantAllowDelivery($registrationData['collection_type'])
                    : $merchant->allow_delivery,
                'allow_collection' => isset($registrationData['collection_collection'])
                    ? $this->doesMerchantAllowCollection($registrationData['collection_type'])
                    : $merchant->allow_collection,
            ]
        );
    }

    public function getMerchantByUrlSlug(string $urlSlug): ?Merchant
    {
        try {
            return $this->getModel()
                ->where('url_slug', $urlSlug)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    /**
     * @param array $addressData
     * @param Merchant|null $merchant
     * @return string
     */
    protected function generateAddressFromRegistrationForm(array $addressData, ?Merchant $merchant): string
    {
        $address = '';

        if (isset($addressData['address-name-number'])) {
            $address .= $addressData['address-name-number'] . ', ';
        }

        if (isset($addressData['address-street'])) {
            $address .= $addressData['address-street'] . ', ';
        }

        if (isset($addressData['address-locality'])) {
            $address .= $addressData['address-locality'] . ', ';
        }

        if (isset($addressData['address-city'])) {
            $address .= $addressData['address-city'] . ', ';
        }

        if (isset($addressData['address-county'])) {
            $address .= $addressData['address-county'] . ', ';
        }

        if (isset($addressData['address-postcode'])) {
            $address .= $addressData['address-postcode'] . ' ';
        }

        if (strlen($address) === 0 && $merchant !== null) {
            $address = $merchant->address;
        }

        return $address;
    }

    protected function doesMerchantAllowDelivery(string $option): int
    {
        if ($option === 'delivery' || $option === 'both') {
            return 1;
        }

        return 0;
    }

    protected function doesMerchantAllowCollection(string $option): int
    {
        if ($option === 'collection' || $option === 'both') {
            return 1;
        }

        return 0;
    }

    protected function getModel(): Merchant
    {
        return $this->model;
    }
}
