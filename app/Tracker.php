<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
class Tracker extends Model
{
    /**
     * @var string $table
     */
    protected $table = 'tracked_changes';

    /**
     * @var array $fillable
     */
    protected $fillable = ['subject_id', 'subject_type', 'user_id', 'name', 'data'];

    /**
     * Get the subject of the activity.
     *
     * @return mixed
     */
    public function subject()
    {
        return $this->morphTo();
    }
}
