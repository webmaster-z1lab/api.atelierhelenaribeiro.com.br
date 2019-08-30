<?php

namespace Modules\Sales\Models;

use App\Models\BaseModel;
use Modules\Stock\Models\ProductStatus;

/**
 * Modules\Sales\Models\Product
 *
 * @property-read string   $id
 * @property-read string   $reference
 * @property-read string   $thumbnail
 * @property-read string   $size
 * @property-read string   $color
 * @property-read integer  $price
 * @property-read string   $status
 * @property-read  integer $amount
 * @property  integer      $sold
 * @property  integer      $returned
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
    public $timestamps = FALSE;

    protected $fillable = [
        'reference',
        'thumbnail',
        'size',
        'color',
        'price',
        'status',
        'amount',
        'sold',
        'returned',
    ];

    protected $casts = [
        'price'    => 'integer',
        'amount'   => 'integer',
        'sold'     => 'integer',
        'returned' => 'integer',
    ];

    protected $attributes = [
        'status'   => ProductStatus::IN_TRANSIT_STATUS,
        'amount'   => 0,
        'sold'     => 0,
        'returned' => 0,
    ];
}
