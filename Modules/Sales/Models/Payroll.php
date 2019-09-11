<?php

namespace Modules\Sales\Models;

use App\Models\BaseModel;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Modules\Customer\Models\Customer;
use Modules\User\Models\User;

/**
 * Modules\Sales\Models\Payroll
 *
 * @property-read string                                   $id
 * @property \Carbon\Carbon                                $date
 * @property integer                                       $total_amount
 * @property integer                                       $total_price
 * @property-read float                                    $total_price_float
 * @property-read \Modules\User\Models\User                $seller
 * @property-read string                                   $seller_id
 * @property-read \Modules\Customer\Models\Customer        $customer
 * @property-read string                                   $customer_id
 * @property-read \Modules\Sales\Models\Visit              $visit
 * @property-read string                                   $visit_id
 * @property-read \Illuminate\Database\Eloquent\Collection $products
 * @property-read \Carbon\Carbon                           $created_at
 * @property-read \Carbon\Carbon                           $updated_at
 * @property-read \Carbon\Carbon                           $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Sales\Models\Sale newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Sales\Models\Sale newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|\Modules\Sales\Models\Sale query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel search($search = NULL)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel searchPaginated($search = NULL, $page = 1, $limit = 10)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = NULL)
 * @mixin \Eloquent
 */
class Payroll extends BaseModel
{
    use SoftDeletes;

    protected $fillable = ['date', 'total_amount', 'total_price'];

    protected $casts = [
        'total_amount' => 'integer',
        'total_price'  => 'integer',
    ];

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsMany
     */
    public function products()
    {
        return $this->embedsMany(Product::class);
    }

    /**
     * @return float
     */
    public function getTotalPriceFloatAttribute(): float
    {
        return floatval($this->attributes['total_price'] / 100.0);
    }
}