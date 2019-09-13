<?php

namespace Modules\Sales\Models;

use App\Models\BaseModel;

/**
 * Modules\Sales\Models\PaymentMethod
 *
 * @property-read string $id
 * @property string      $method
 * @property integer     $value
 * @property-read float  $value_float
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\Modules\Sales\Models\PaymentMethod newModelQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\Modules\Sales\Models\PaymentMethod newQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\Modules\Sales\Models\PaymentMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel search($search = NULL)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel searchPaginated($search = NULL, $page = 1, $limit = 10)
 * @mixin \Eloquent
 */
class PaymentMethod extends BaseModel
{
    public $timestamps = FALSE;

    protected $fillable = ['method', 'value'];

    protected $casts = ['value' => 'integer'];

    /**
     * @return float
     */
    public function getValueFloatAttribute(): float
    {
        return (float) ($this->attributes['value'] / 100.0);
    }
}
