<?php

namespace Modules\Stock\Models;

use App\Models\BaseModel;

/**
 * Modules\Stock\Models\Color
 *
 * @property-read string $id
 * @property string      $name
 * @property string      $value
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Stock\Models\Product newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Stock\Models\Product newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Stock\Models\Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = NULL)
 * @mixin \Eloquent
 */
class Color extends BaseModel
{
    public $timestamps = FALSE;

    protected $fillable = ['name', 'value'];
}
