<?php

namespace Modules\Customer\Models;

use App\Models\Address;
use App\Models\BaseModel;
use App\Models\Phone;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Modules\User\Models\User;

/**
 * Modules\Customer\Models\Customer
 *
 * @property-read string                                                                    $id
 * @property-read string                                                                    $seller_id
 * @property string                                                                         $company_name
 * @property string                                                                         $trading_name
 * @property string                                                                         $document
 * @property string                                                                         $state_registration
 * @property string                                                                         $municipal_registration
 * @property string                                                                         $email
 * @property string                                                                         $contact
 * @property string                                                                         $status
 * @property string                                                                         $annotation
 * @property-read  \Modules\User\Models\User                                                $seller
 * @property-read  \App\Models\Address                                                      $address
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Phone[]              $phones
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Customer\Models\Owner[] $owners
 * @property-read \Carbon\Carbon                                                            $created_at
 * @property-read \Carbon\Carbon                                                            $updated_at
 * @property-read \Carbon\Carbon                                                            $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Customer\Models\Customer newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Customer\Models\Customer newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Customer\Models\Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = NULL)
 * @mixin \Eloquent
 */
class Customer extends BaseModel implements CustomerInterface
{
    use SoftDeletes;

    protected $fillable = [
        'company_name',
        'trading_name',
        'document',
        'state_registration',
        'municipal_registration',
        'email',
        'contact',
        'status',
        'annotation',
    ];

    protected $attributes = [
        'status' => self::STATUS_ACTIVE,
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
        return $this->embedsMany(Owner::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seller()
    {
        return $this->belongsTo(User::class);
    }
}
