<?php

namespace App\Models;

/**
 * Class Price
 *
 * @package App\Models
 * @property int            $price
 * @property \Carbon\Carbon $started_at
 * @property-read string    $id
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\Price newModelQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\Price newQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\Price query()
 * @mixin \Eloquent
 */
class Price extends BaseModel
{
    public $timestamps = FALSE;

    protected $fillable = [
        'price',
        'started_at',
    ];

    protected $casts = [
        'price' => 'integer',
    ];

    protected $dates = ['started_at'];
}
