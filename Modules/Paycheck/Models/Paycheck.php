<?php

namespace Modules\Paycheck\Models;

use App\Models\BaseModel;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Modules\Sales\Models\Visit;

/**
 * Modules\Paycheck\Models\Paycheck
 *
 * @property-read string                      $id
 * @property string                           $holder
 * @property string                           $document
 * @property string                           $bank
 * @property string                           $number
 * @property string                           $output
 * @property string                           $received_by
 * @property integer                          $value
 * @property-read float                       $value_float
 * @property-read \Carbon\Carbon              $pay_date
 * @property-read \Carbon\Carbon              $received_at
 * @property-read \Carbon\Carbon              $created_at
 * @property-read \Carbon\Carbon              $updated_at
 * @property-read \Carbon\Carbon              $deleted_at
 * @property-read \Modules\Sales\Models\Visit $visit
 * @property-read string                      $visit_id
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\Modules\Paycheck\Models\Paycheck newModelQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\Modules\Paycheck\Models\Paycheck newQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\Modules\Paycheck\Models\Paycheck query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel search($search = NULL)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel searchPaginated($search = NULL, $page = 1, $limit = 10)
 * @mixin \Eloquent
 */
class Paycheck extends BaseModel
{
    use SoftDeletes;

    protected $fillable = ['holder', 'document', 'bank', 'number', 'pay_date', 'value', 'output', 'received_at', 'received_by'];

    protected $casts = ['value' => 'integer'];

    protected $dates = ['pay_date', 'received_at'];

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
    public function getValueFloatAttribute(): float
    {
        return (float) ($this->attributes['value'] / 100.0);
    }
}
