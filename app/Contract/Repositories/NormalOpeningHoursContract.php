<?php

namespace App\Contract\Repositories;

use App\NormalOpeningHour;
use Illuminate\Database\Eloquent\Collection;

interface NormalOpeningHoursContract
{
    /**
     * @param int $merchantId
     * @return Collection
     */
    public function createDefaultOpeningHoursForMerchant(int $merchantId): Collection;

    /**
     * @param int $merchantId
     * @return void
     */
    public function clearCurrentOpeningHoursForMerchant(int $merchantId): void;

    /**
     * @param array $days
     * @param int $merchantId
     * @return NormalOpeningHour|null
     */
    public function createNormalOpeningHours(array $days, int $merchantId): ?NormalOpeningHour;

    /**
     * returns the opening hours for the current merchant
     * @param int $merchantId
     * @return Collection
     */
    public function getOpeningHoursForMerchant(int $merchantId): Collection;

    /**
     * returns an array in a format that is useable on the frontend
     * @param Collection $openingHours
     * @return array
     */
    public function formatOpeningHoursForForm(Collection $openingHours): array;
}
