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
 * @property-read string                                                       $id
 * @property-read string                                                       $template_id
 * @property string                                                            $thumbnail
 * @property string                                                            $barcode
 * @property string                                                            $size
 * @property string                                                            $color
 * @property boolean                                                           $is_processed
 * @property-read \Modules\Catalog\Models\Template                             $template
 * @property \App\Models\Price                                                 $price
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

    private const PROCESSED_STATUS = FALSE;

    protected $fillable = [
        'barcode',
        'thumbnail',
        'size',
        'color',
        'is_processed',
    ];

    protected $attributes = [
        'is_processed' => self::PROCESSED_STATUS,
    ];

    protected $casts = [
        'is_processed' => 'boolean',
    ];

    /**
     * @return \App\Models\Price |null
     */
    public function getPriceAttribute()
    {
        return $this->prices->sortByDesc('started_at')->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function images()
    {
        return $this->belongsToMany(Image::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsMany
     */
    public function prices()
    {
        return $this->embedsMany(Price::class);
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value)
    {
        return $this->find($value) ?? abort(404);
    }
}
