<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
class Provider extends Model
{

    /**
     * @return BelongsToMany
     */
    public function merchants(): BelongsToMany
    {
        return $this->belongsToMany(Merchant::class, 'merchant_payment');
    }
}
