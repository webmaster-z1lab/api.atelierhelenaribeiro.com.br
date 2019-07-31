<?php

namespace Modules\Customer\Models;

use App\Models\BaseModel;
use App\Models\Phone;

/**
 * Modules\Customer\Models\Person
 *
 * @property-read string                                                  $id
 * @property string                                                       $name
 * @property string                                                       $email
 * @property \Carbon\Carbon                                               $birth_date
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Phone[] $phones
 * @property-read \Carbon\Carbon                                          $created_at
 * @property-read \Carbon\Carbon                                          $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Customer\Models\Person newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Customer\Models\Person newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Customer\Models\Person query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = NULL)
 * @mixin \Eloquent
 */
class Person extends BaseModel
{
    protected $fillable = [
        'name',
        'birth_date',
        'email',
    ];

    protected $dates = ['birth_date'];

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsMany
     */
    public function phones()
    {
        return $this->embedsMany(Phone::class);
    }
}
