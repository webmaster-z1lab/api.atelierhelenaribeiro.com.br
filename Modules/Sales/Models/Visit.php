<?php

namespace Modules\Sales\Models;

use App\Models\BaseModel;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Modules\Customer\Models\Customer;
use Modules\User\Models\User;

/**
 * Modules\Sales\Models\Visit
 *
 * @property-read string                                                                         $id
 * @property-read  string                                                                        $annotations
 * @property-read string                                                                         $status
 * @property-read  integer                                                                       $discount
 * @property-read float                                                                          $discount_float
 * @property-read  integer                                                                       $total_amount
 * @property-read  integer                                                                       $total_price
 * @property-read float                                                                          $total_price_float
 * @property-read  \Carbon\Carbon                                                                $date
 * @property-read \Carbon\Carbon                                                                 $created_at
 * @property-read \Carbon\Carbon                                                                 $updated_at
 * @property-read \Carbon\Carbon                                                                 $deleted_at
 * @property-read \Modules\User\Models\User                                                      $seller
 * @property-read string                                                                         $seller_id
 * @property-read \Modules\Customer\Models\Customer                                              $customer
 * @property-read string                                                                         $customer_id
 * @property-read \Modules\Sales\Models\Packing                                                  $packing
 * @property-read string                                                                         $packing_id
 * @property-read \Modules\Sales\Models\Information                                              $sale
 * @property-read \Modules\Sales\Models\Information                                              $refund
 * @property-read \Modules\Sales\Models\Information                                              $payroll
 * @property-read \Modules\Sales\Models\Information                                              $payroll_sale
 * @property-read \Modules\Sales\Models\Information                                              $payroll_refund
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Sales\Models\PaymentMethod[] $payment_methods
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\Modules\Sales\Models\Visit newModelQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\Modules\Sales\Models\Visit newQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\Modules\Sales\Models\Visit query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel search($search = NULL)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel searchPaginated($search = NULL, $page = 1, $limit = 10)
 * @mixin \Eloquent
 */
class Visit extends BaseModel
{
    use SoftDeletes;

    public const OPENED_STATUS    = 'opened';
    public const FINALIZED_STATUS = 'finalized';
    public const CLOSED_STATUS    = 'closed';

    protected $fillable = ['annotations', 'date', 'discount', 'total_amount', 'total_price', 'status'];

    protected $casts = [
        'discount'     => 'integer',
        'total_amount' => 'integer',
        'total_price'  => 'integer',
    ];

    protected $dates = ['date'];

    protected $attributes = [
        'discount'     => 0,
        'total_amount' => 0,
        'total_price'  => 0,
        'status'       => self::OPENED_STATUS,
    ];

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
    public function packing()
    {
        return $this->belongsTo(Packing::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function sale()
    {
        return $this->embedsOne(Information::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function refund()
    {
        return $this->embedsOne(Information::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function payroll()
    {
        return $this->embedsOne(Information::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function payroll_sale()
    {
        return $this->embedsOne(Information::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function payroll_refund()
    {
        return $this->embedsOne(Information::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsMany
     */
    public function payment_methods()
    {
        return $this->embedsMany(PaymentMethod::class);
    }

    /**
     * @return float
     */
    public function getDiscountFloatAttribute(): float
    {
        return (float) ($this->attributes['discount'] / 100.0);
    }

    /**
     * @return float
     */
    public function getTotalPriceFloatAttribute(): float
    {
        return (float) ($this->attributes['total_price'] / 100.0);
    }
}
