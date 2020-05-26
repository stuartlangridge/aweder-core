<?php

namespace App\Repository;

use App\Contract\Repositories\NormalOpeningHoursContract;
use App\NormalOpeningHour;
use Illuminate\Database\Eloquent\Collection;
use Psr\Log\LoggerInterface;

class NormalOpeningHoursRepository implements NormalOpeningHoursContract
{
    /**
     * @var NormalOpeningHour
     */
    protected $model;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(NormalOpeningHour $model, LoggerInterface $logger)
    {
        $this->model = $model;

        $this->logger = $logger;
    }

    public function createDefaultOpeningHoursForMerchant(int $merchantId): Collection
    {
        $defaultHours = [
            'monday' => [
                'opening' => [
                    'hour' => '09',
                    'minute' => '00',
                ],
                'closing' => [
                    'hour' => '17',
                    'minute' => '00',
                ],
            ],
            'tuesday' => [
                'opening' => [
                    'hour' => '09',
                    'minute' => '00',
                ],
                'closing' => [
                    'hour' => '17',
                    'minute' => '00',
                ],
            ],
            'wednesday' => [
                'opening' => [
                    'hour' => '09',
                    'minute' => '00',
                ],
                'closing' => [
                    'hour' => '17',
                    'minute' => '00',
                ],
            ],
            'thursday' => [
                'opening' => [
                    'hour' => '09',
                    'minute' => '00',
                ],
                'closing' => [
                    'hour' => '17',
                    'minute' => '00',
                ],
            ],
            'friday' => [
                'opening' => [
                    'hour' => '09',
                    'minute' => '00',
                ],
                'closing' => [
                    'hour' => '17',
                    'minute' => '00',
                ],
            ],
            'saturday' => [
                'opening' => [
                    'hour' => '09',
                    'minute' => '00',
                ],
                'closing' => [
                    'hour' => '17',
                    'minute' => '00',
                ],
            ],
            'sunday' => [
                'opening' => [
                    'hour' => '09',
                    'minute' => '00',
                ],
                'closing' => [
                    'hour' => '17',
                    'minute' => '00',
                ],
            ],
        ];

        if ($this->createNormalOpeningHours($defaultHours, $merchantId)) {
            return $this->getOpeningHoursForMerchant($merchantId);
        }
        return new Collection();
    }

    public function clearCurrentOpeningHoursForMerchant(int $merchantId): void
    {
        $this->getModel()->where('merchant_id', $merchantId)->delete();
    }

    public function createNormalOpeningHours(array $days, int $merchantId): ?NormalOpeningHour
    {
        $merchant_hours = null;

        foreach ($days as $day_of_week => $day) {
            $openingHours =  $closingHours = null;
            if (isset($day['opening'])) {
                $openingHours = $day['opening']['hour'] . ':' . $day['opening']['minute'];
            }

            if (isset($day['closing'])) {
                $closingHours = $day['closing']['hour'] . ':' . $day['closing']['minute'];
            }

            $merchant_hours = $this->getModel()->create([
                'day_of_week' => $this->convertDayNameToInteger($day_of_week),
                'merchant_id' => $merchantId,
                'open_time' => $openingHours,
                'close_time' => $closingHours
            ]);
        }

        return $merchant_hours;
    }

    public function getOpeningHoursForMerchant(int $merchantId): Collection
    {
        return $this->getModel()->where('merchant_id', $merchantId)->get();
    }

    public function convertDayNameToInteger($name): ?int
    {
        switch ($name) {
            case 'monday':
                return 1;
            case 'tuesday':
                return 2;
            case 'wednesday':
                return 3;
            case 'thursday':
                return 4;
            case 'friday':
                return 5;
            case 'saturday':
                return 6;
            case 'sunday':
                return 7;
        }
        return null;
    }

    public function formatOpeningHoursForForm(Collection $openingHours): array
    {
        $formattedHours = [];

        $openingHours->each(function ($openingHour) use (&$formattedHours) {
            $formattedHours[$this->convertDayNumberToDayName($openingHour->day_of_week)] = [
                'opening' => [
                    'hour' => $openingHour->open_time->format('H'),
                    'minute' => $openingHour->open_time->format('i'),
                ],
                'closing' => [
                    'hour' => $openingHour->close_time->format('H'),
                    'minute' => $openingHour->close_time->format('i'),
                ],
            ];
        });

        return $formattedHours;
    }

    protected function convertDayNumberToDayName(int $dayInt): string
    {
        switch ($dayInt) {
            case 1:
            default:
                $day = 'monday';
                break;
            case 2:
                $day = 'tuesday';
                break;
            case 3:
                $day = 'wednesday';
                break;
            case 4:
                $day = 'thursday';
                break;
            case 5:
                $day = 'friday';
                break;
            case 6:
                $day = 'saturday';
                break;
            case 7:
                $day = 'sunday';
                break;
        }

        return $day;
    }

    protected function getModel(): NormalOpeningHour
    {
        return $this->model;
    }
}
