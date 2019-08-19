<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Price
 *
 * @package App\Models
 * @property-read string    $id
 * @property int            $price
 * @property float          $price_float
 * @property \Carbon\Carbon $started_at
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\Price newModelQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\Price newQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\Price query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = NULL)
 * @mixin \Eloquent
 */
class Price extends Model
{
    public $timestamps = FALSE;

    protected $fillable = [
        'price',
        'started_at',
    ];

    protected $casts = [
        'price' => 'integer',
    ];

    protected $dates = ['started_at'];

    /**
     * @param $value
     */
    public function setPriceAttribute(float $value)
    {
        $this->attributes['price'] = intval($value * 100);
    }

    /**
     * @return float
     */
    public function getPriceFloatAttribute()
    {
        return floatval($this->attributes['price'] / 100);
    }
}
