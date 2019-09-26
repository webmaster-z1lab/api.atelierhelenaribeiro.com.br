<?php

namespace Modules\Sales\Models;

use App\Models\BaseModel;
use App\Traits\FileUrl;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Modules\Customer\Models\Customer;
use Modules\User\Models\User;

/**
 * Modules\Sales\Models\Return
 *
 * @property-read string                            $id
 * @property-read \Carbon\Carbon                    $date
 * @property-read integer                           $price
 * @property-read float                             $price_float
 * @property-read string                            $reference
 * @property-read string                            $thumbnail
 * @property-read string                            $thumbnail_url
 * @property-read string                            $size
 * @property-read string                            $color
 * @property-read \Modules\Stock\Models\Product     $product
 * @property-read string                            $product_id
 * @property-read \Modules\User\Models\User         $seller
 * @property-read string                            $seller_id
 * @property-read \Modules\Customer\Models\Customer $customer
 * @property-read string                            $customer_id
 * @property-read \Modules\Sales\Models\Visit       $visit
 * @property-read string                            $visit_id
 * @property-read \Carbon\Carbon                    $created_at
 * @property-read \Carbon\Carbon                    $updated_at
 * @property-read \Carbon\Carbon                    $deleted_at
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\Modules\Sales\Models\Sale newModelQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\Modules\Sales\Models\Sale newQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\Modules\Sales\Models\Sale query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel search($search = NULL)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel searchPaginated($search = NULL, $page = 1, $limit = 10)
 * @mixin \Eloquent
 */
class Refund extends BaseModel
{
    use SoftDeletes, FileUrl;

    protected $fillable = ['date', 'reference', 'thumbnail', 'size', 'color', 'price'];

    protected $casts = [
        'price' => 'integer',
    ];

    protected $dates = ['date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(\Modules\Stock\Models\Product::class);
    }

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
     * @return float
     */
    public function getPriceFloatAttribute(): float
    {
        return floatval($this->attributes['total_price'] / 100.0);
    }

    /**
     * @return string
     */
    public function getThumbnailUrlAttribute(): string
    {
        return (isset($this->attributes['thumbnail']))
            ? $this->fileUrl($this->attributes['thumbnail'])
            : config('image.sizes.thumbnail.placeholder');
    }
}
