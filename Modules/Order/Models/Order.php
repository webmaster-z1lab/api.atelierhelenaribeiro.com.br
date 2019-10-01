<?php

namespace Modules\Order\Models;

use App\Models\BaseModel;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Modules\Customer\Models\Customer;
use Modules\Stock\Models\Product;

/**
 * Modules\Order\Models\Order
 *
 * @property-read string                                                                   $id
 * @property string                                                                        $status
 * @property string                                                                        $annotations
 * @property string                                                                        $tracking_code
 * @property integer                                                                       $freight
 * @property-read float                                                                    $freight_float
 * @property integer                                                                       $total_price
 * @property-read float                                                                    $total_price_float
 * @property-read \Carbon\Carbon                                                           $event_date
 * @property-read \Carbon\Carbon                                                           $ship_until
 * @property-read \Carbon\Carbon                                                           $shipped_at
 * @property-read \Carbon\Carbon                                                           $created_at
 * @property-read \Carbon\Carbon                                                           $updated_at
 * @property-read \Carbon\Carbon                                                           $deleted_at
 * @property-read \Modules\Customer\Models\Customer                                        $customer
 * @property-read string                                                                   $customer_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Stock\Models\Product[] $products
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\Modules\Order\Models\Order newModelQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\Modules\Order\Models\Order newQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\Modules\Order\Models\Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel search($search = NULL)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel searchPaginated($search = NULL, $page = 1, $limit = 10)
 * @mixin \Eloquent
 */
class Order extends BaseModel
{
    use SoftDeletes;

    public const AWAITING_STATUS      = 'awaiting';
    public const IN_PRODUCTION_STATUS = 'in production';
    public const READY_STATUS         = 'ready for shipping';
    public const SHIPPED_STATUS       = 'shipped';

    protected $fillable = ['status', 'event_date', 'ship_until', 'shipped_at', 'annotations', 'freight', 'total_price', 'tracking_code'];

    protected $casts = [
        'freight'     => 'integer',
        'total_price' => 'integer',
    ];

    protected $dates = ['event_date', 'ship_until', 'shipped_at'];

    protected $attributes = [
        'status' => self::AWAITING_STATUS,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * @return float
     */
    public function getFreightFloatAttribute(): float
    {
        if (array_key_exists('freight', $this->attributes)) {
            return (float) ($this->attributes['freight'] / 100.0);
        }

        return 0.0;
    }

    /**
     * @return float
     */
    public function getTotalPriceFloatAttribute(): float
    {
        return (float) ($this->attributes['total_price'] / 100.0);
    }
}
