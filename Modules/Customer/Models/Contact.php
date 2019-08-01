<?php

namespace Modules\Customer\Models;

use App\Models\BaseModel;

/**
 * Modules\Customer\Models\Contact
 *
 * @property-read string         $id
 * @property string              $name
 * @property-read \Carbon\Carbon $created_at
 * @property-read \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Customer\Models\Owner newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Customer\Models\Owner newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Customer\Models\Owner query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = NULL)
 * @mixin \Eloquent
 */
class Contact extends BaseModel
{
    protected $fillable = ['name'];
}
