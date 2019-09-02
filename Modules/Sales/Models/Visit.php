<?php

namespace Modules\Sales\Models;

use App\Models\BaseModel;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Modules\Customer\Models\Customer;
use Modules\User\Models\User;

/**
 * Modules\Sales\Models\Visit
 *
 * @property-read string                            $id
 * @property string                                 $annotations
 * @property \Carbon\Carbon                         $date
 * @property-read \Modules\Customer\Models\Customer $customer
 * @property-read \Modules\User\Models\User         $seller
 * @property-read \Carbon\Carbon                    $created_at
 * @property-read \Carbon\Carbon                    $updated_at
 * @property-read \Carbon\Carbon                    $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Sales\Models\Visit newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Sales\Models\Visit newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Sales\Models\Visit query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel search($search = NULL)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel searchPaginated($search = NULL, $page = 1, $limit = 10)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = NULL)
 * @mixin \Eloquent
 */
class Visit extends BaseModel
{
    use SoftDeletes;

    protected $fillable = ['annotations', 'date'];

    protected $dates = ['date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seller()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
