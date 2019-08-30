<?php

namespace Modules\Stock\Models;

use App\Models\BaseModel;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

/**
 * \Modules\Stock\Models\Size
 *
 * @property-read string         $id
 * @property-read string         $reference
 * @property string              $name
 * @property-read \Carbon\Carbon $created_at
 * @property-read \Carbon\Carbon $updated_at
 * @property-read \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Stock\Models\Size newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Stock\Models\Size newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Stock\Models\Size query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel search($search = NULL)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel searchPaginated($search = NULL, $page = 1, $limit = 10)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = NULL)
 * @mixin \Eloquent
 */
class Size extends BaseModel
{
    use SoftDeletes;

    public const REFERENCE_LENGTH = 2;

    protected $fillable = ['name', 'reference'];
}
