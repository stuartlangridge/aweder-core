<?php

namespace App;

use App\Traits\HelperTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * App\Inventory
 *
 * @property int $id
 * @property int $merchant_id
 * @property int $category_id
 * @property string $title
 * @property string|null $description
 * @property int $price
 * @property string $image
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
class Inventory extends Model
{
    use SoftDeletes;
    use HelperTrait;

    protected $fillable = [
        'merchant_id',
        'category_id',
        'title',
        'description',
        'price',
        'available',
        'image'
    ];

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * @return string
     */
    public function getTemporaryInventoryImageLink(): string
    {
        return Storage::temporaryUrl($this->image, now()->addMinutes(10));
    }
}
