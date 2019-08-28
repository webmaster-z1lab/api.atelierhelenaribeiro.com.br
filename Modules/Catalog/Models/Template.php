<?php

namespace Modules\Catalog\Models;

use App\Models\BaseModel;
use App\Models\Image;
use App\Models\Price;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Modules\Stock\Models\Product;

/**
 * Class Template
 *
 * @package Modules\Catalog\Models
 * @property-read string                              $id
 * @property string                                   $reference
 * @property string                                   $thumbnail
 * @property boolean                                  $is_active
 * @property boolean                                  $is_processed
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

    public const  REFERENCE_LENGTH = 8;
    public const  STATUS_ACTIVE    = TRUE;
    private const PROCESSED_STATUS = FALSE;

    protected $fillable = [
        'reference',
        'is_active',
        'thumbnail',
        'is_processed',
    ];

    protected $attributes = [
        'is_active'    => self::STATUS_ACTIVE,
        'is_processed' => self::PROCESSED_STATUS,
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_processed' => 'boolean',
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|\Jenssegers\Mongodb\Relations\EmbedsMany|object|null
     */
    public function getPriceAttribute()
    {
        return $this->prices->sortByDesc('started_at')->first();
    }
}
