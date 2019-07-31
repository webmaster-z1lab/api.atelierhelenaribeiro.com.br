<?php

namespace Modules\Customer\Models;

use App\Models\Address;
use App\Models\BaseModel;
use App\Models\Phone;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

/**
 * Modules\Customer\Models\Customer
 *
 * @property-read string                                                  $id
 * @property string                                                       $company_name
 * @property string                                                       $trading_name
 * @property string                                                       $document
 * @property string                                                       $state_registration
 * @property string                                                       $municipal_registration
 * @property string                                                       $email
 * @property \App\Models\Address                                          $address
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Phone[] $phones
 * @property-read \Carbon\Carbon                                          $created_at
 * @property-read \Carbon\Carbon                                          $updated_at
 * @property-read \Carbon\Carbon                                          $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Customer\Models\Customer newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Customer\Models\Customer newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Customer\Models\Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = NULL)
 * @mixin \Eloquent
 */
class Customer extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'company_name',
        'trading_name',
        'document',
        'state_registration',
        'municipal_registration',
        'email',
    ];

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function address()
    {
        return $this->embedsOne(Address::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsMany
     */
    public function phones()
    {
        return $this->embedsMany(Phone::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsMany
     */
    public function owners()
    {
        return $this->embedsMany(Person::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsMany
     */
    public function responsibles()
    {
        return $this->embedsMany(Person::class);
    }
}
