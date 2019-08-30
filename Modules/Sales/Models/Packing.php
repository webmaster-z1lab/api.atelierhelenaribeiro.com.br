<?php

namespace Modules\Sales\Models;

use App\Models\BaseModel;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Modules\User\Models\User;

/**
 * Modules\Sales\Models\Packing
 *
 * @property-read mixed                                                                    $id
 * @property \Carbon\Carbon                                                                $checked_out_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Sales\Models\Product[] $products
 * @property-read \Modules\User\Models\User                                                $seller
 * @property-read \Carbon\Carbon                                                           $created_at
 * @property-read \Carbon\Carbon                                                           $updated_at
 * @property-read \Carbon\Carbon                                                           $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Sales\Models\Packing newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Sales\Models\Packing newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Sales\Models\Packing query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel search($search = NULL)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel searchPaginated($search = NULL, $page = 1, $limit = 10)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = NULL)
 * @mixin \Eloquent
 */
class Packing extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [];

    protected $dates = ['checked_out_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seller()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsMany
     */
    public function products()
    {
        return $this->embedsMany(Product::class);
    }
}
