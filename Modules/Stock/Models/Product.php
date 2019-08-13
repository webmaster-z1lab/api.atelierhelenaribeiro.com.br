<?php

namespace Modules\Stock\Models;

use App\Models\Image;
use App\Models\Price;
use Jenssegers\Mongodb\Eloquent\Model;
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
 * @method static \Modules\Stock\Models\Product newModelQuery()
 * @method static \Modules\Stock\Models\Product newQuery()
 * @method static \Modules\Stock\Models\Product query()
 * @mixin \Eloquent
 */
class Product extends Model
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function prices()
    {
        return $this->embedsMany(Price::class);
    }

    /**
     * @return \App\Models\Price |null
     */
    public function getPriceAttribute()
    {
        return $this->prices->sortByDesc('started_at')->first();
    }
}
