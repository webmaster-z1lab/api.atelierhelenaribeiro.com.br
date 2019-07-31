<?php

namespace App\Models;

/**
 * Modules\User\Models\Address
 *
 * @property-read string $id
 * @property string      $street
 * @property integer     $number
 * @property string      $complement
 * @property string      $district
 * @property string      $postal_code
 * @property string      $city
 * @property string      $state
 * @property string      $formatted
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\Address newModelQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\Address newQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\Address query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = NULL)
 * @mixin \Eloquent
 */
class Address extends BaseModel
{
    public $timestamps = FALSE;

    protected $fillable = [
        'street',
        'number',
        'complement',
        'district',
        'postal_code',
        'city',
        'state',
    ];

    protected $casts = [
        'number' => 'integer',
    ];

    /**
     * @return string
     */
    public function getFormattedAttribute()
    {
        return "{$this->street}, {$this->number} - {$this->district}, {$this->complement} ".PHP_EOL.
               "{$this->city}, {$this->state} - {$this->postal_code}";
    }
}
