<?php

namespace Modules\Stock\Models;

use App\Models\BaseModel;
use App\Models\Image;
use App\Models\Price;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Modules\Catalog\Models\Template;

/**
 * Modules\Stock\Models\Product
 *
 * @property-read mixed                                                        $id
 * @property string                                                            $barcode
 * @property string                                                            $size
 * @property-read \Modules\Catalog\Models\Template                             $template
 * @property \App\Models\Price                                                 $price
 * @property \Modules\Stock\Models\Color                                       $color
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Image[] $images
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Price[] $prices
 * @property-read \Carbon\Carbon                                               $created_at
 * @property-read \Carbon\Carbon                                               $updated_at
 * @property-read \Carbon\Carbon                                               $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Stock\Models\Product newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Stock\Models\Product newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Stock\Models\Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = NULL)
 * @mixin \Eloquent
 */
class Product extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'barcode', 'size',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function color()
    {
        return $this->embedsOne(Color::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

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
