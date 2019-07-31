<?php

namespace App\Models;

/**
 * Class Price
 *
 * @package App\Models
 * @property-read string    $id
 * @property int            $price
 * @property \Carbon\Carbon $started_at
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\Price newModelQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\Price newQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\Price query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = NULL)
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
