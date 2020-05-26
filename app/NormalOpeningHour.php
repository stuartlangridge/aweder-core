<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
class NormalOpeningHour extends Model
{

    protected $fillable = [
        'merchant_id',
        'day_of_week',
        'open_time',
        'close_time',
    ];

    protected $dates = [
        'open_time',
        'close_time',
    ];

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }
}
