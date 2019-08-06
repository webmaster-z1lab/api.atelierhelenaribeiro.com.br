<?php

namespace Modules\Customer\Models;

use App\Models\BaseModel;
use App\Models\Phone;
use Carbon\Carbon;

/**
 * Modules\Customer\Models\Owner
 *
 * @property-read string                            $id
 * @property string                                 $name
 * @property string                                 $document
 * @property string                                 $email
 * @property \Carbon\Carbon                         $birth_date
 * @property-read \App\Models\Phone                 $phone
 * @property-read \Carbon\Carbon                    $created_at
 * @property-read \Carbon\Carbon                    $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Customer\Models\Owner newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Customer\Models\Owner newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Customer\Models\Owner query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = NULL)
 * @mixin \Eloquent
 */
class Owner extends BaseModel
{
    protected $fillable = [
        'name',
        'document',
        'birth_date',
        'email',
    ];

    protected $dates = ['birth_date'];

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function phone()
    {
        return $this->embedsOne(Phone::class);
    }
}
