<?php

namespace Modules\Catalog\Models;

use App\Models\BaseModel;
use App\Models\Image;
use App\Models\Price;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

/**
 * Class Template
 *
 * @package Modules\Catalog\Models
 * @property-read string                              $id
 * @property string                                   $reference
 * @property \Illuminate\Database\Eloquent\Collection $images
 * @property \Illuminate\Database\Eloquent\Collection $prices
 * @property \App\Models\Price                        $price
 * @property-read \Carbon\Carbon                      $created_at
 * @property-read \Carbon\Carbon                      $updated_at
 * @property-read \Carbon\Carbon                      $deleted_at
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\Modules\Catalog\Models\Template newModelQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\Modules\Catalog\Models\Template newQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\Modules\Catalog\Models\Template query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = NULL)
 * @mixin \Eloquent
 */
class Template extends BaseModel
{
    use SoftDeletes;

    public const REFERENCE_LENGTH = 8;

    protected $fillable = [
        'reference',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsMany
     */
    public function prices()
    {
        return $this->embedsMany(Price::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|\Jenssegers\Mongodb\Relations\EmbedsMany|object|null
     */
    public function getPriceAttribute()
    {
        return $this->prices()->orderBy('started_at', 'DESC')->first();
    }
}
