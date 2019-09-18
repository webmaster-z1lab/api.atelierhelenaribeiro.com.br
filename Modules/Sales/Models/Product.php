<?php

namespace Modules\Sales\Models;

use App\Models\BaseModel;
use App\Traits\FileUrl;
use Modules\Stock\Models\ProductStatus;

/**
 * Modules\Sales\Models\Product
 *
 * @property-read string   $id
 * @property-read string   $product_id
 * @property-read string   $reference
 * @property-read string   $thumbnail
 * @property-read string   $thumbnail_url
 * @property-read string   $size
 * @property-read string   $color
 * @property-read integer  $price
 * @property-read string   $status
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Sales\Models\Product newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Sales\Models\Product newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Sales\Models\Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel search($search = NULL)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel searchPaginated($search = NULL, $page = 1, $limit = 10)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = NULL)
 * @mixin \Eloquent
 */
class Product extends BaseModel
{
    use FileUrl;

    public $timestamps = FALSE;

    protected $fillable = [
        'product_id',
        'reference',
        'thumbnail',
        'size',
        'color',
        'price',
        'status',
    ];

    protected $casts = [
        'price'    => 'integer',
    ];

    protected $attributes = [
        'status'   => ProductStatus::IN_TRANSIT_STATUS,
    ];

    /**
     * @return string
     */
    public function getThumbnailUrlAttribute()
    {
        return (isset($this->attributes['thumbnail']))
            ? $this->fileUrl($this->attributes['thumbnail'])
            : config('image.sizes.thumbnail.placeholder');
    }
}
