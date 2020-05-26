<?php

namespace App\Traits;

use App\Tracker;
use Illuminate\Support\Facades\Log;
use ReflectionClass;
use ReflectionException;

trait TrackChanges
{
    /**
     * Register the necessary event listeners.
     *
     * @return bool
     */
    protected static function bootTrackChanges(): bool
    {
        $events = static::getModelEvents();

        if ($events === null) {
            return false;
        }

        foreach ($events as $event) {
            static::$event(function ($model) use ($event) {
                $model->trackChanges($event);
            });
        }

        return true;
    }

    /**
     * Track changes for the model.
     *
     * @param string $event
     *
     * @return bool
     */
    public function trackChanges(string $event): bool
    {
        $data = $this->getData($this, $event);

        if (empty($data)) {
            return false;
        }

        if (auth()->user()) {
            $user = auth()->user()->id;
        }

        Tracker::create([
            'subject_id' => $this->getAttribute('id'),
            'subject_type' => get_class($this),
            'user_id' => $user ?? null,
            'name' => $this->getName($this, $event),
            'data' => json_encode($data)
        ]);

        return true;
    }

    /**
     * Returns the name of the tracked change.
     *
     * @param $model
     * @param string $action
     *
     * @return string
     */
    protected function getName($model, string $action): ?string
    {
        try {
            $name = strtolower((new ReflectionClass($model))->getShortName());
        } catch (ReflectionException $exception) {
            Log::error('An issue occurred when getting the activity name Exception:: ' . $exception->getMessage());
            return null;
        }

        return $action . '_' . $name;
    }

    /**
     * Get the model events to track
     *
     * @return array
     */
    protected static function getModelEvents(): array
    {
        if (isset(static::$tracking_events)) {
            return static::$tracking_events;
        }
        return [
            'created', 'deleted', 'updated'
        ];
    }

    /**
     * Get the model data to track
     *
     * @return array|null
     */
    protected static function getModelFields(): ?array
    {
        if (isset(static::$filtered_data)) {
            return static::$filtered_data;
        }
        return null;
    }

    /**
     *  returns the data with the given filters
     *
     * @param $model
     * @param string $event
     *
     * @return array
     */
    protected function getData($model, string $event): array
    {
        $fields = self::getModelFields();

        $updated_values = [];

        if (is_array($fields)) {
            if ($event === 'updated') {
                foreach ($model->getAttributes() as $key => $entry) {
                    if ($model->getOriginal($key) !== $entry && in_array($key, $fields, true)) {
                        $updated_values[$key] = $entry;
                    }
                }
            } else {
                foreach ($fields as $field) {
                    $updated_values[$field] = $model->getAttribute($field);
                }
            }
        }
        return $updated_values;
    }
}
