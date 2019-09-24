<?php

namespace Modules\Sales\Models;

use App\Models\BaseModel;

/**
 * Modules\Sales\Models\Information
 *
 * @property-read string $id
 * @property-read float  $price_float
 * @property integer     $amount
 * @property integer     $price
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\Modules\Sales\Models\Information newModelQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\Modules\Sales\Models\Information newQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\Modules\Sales\Models\Information query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel search($search = NULL)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel searchPaginated($search = NULL, $page = 1, $limit = 10)
 * @mixin \Eloquent
 */
class Information extends BaseModel
{
    public $timestamps = FALSE;

    protected $fillable = ['amount', 'price'];

    protected $casts = [
        'amount' => 'integer',
        'price'  => 'integer',
    ];

    protected $attributes = [
        'amount' => 0,
        'price'  => 0,
    ];

    /**
     * @return float
     */
    public function getPriceFloatAttribute(): float
    {
        return (float) ($this->attributes['price'] / 100.0);
    }
}
