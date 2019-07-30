<?php

namespace App\Models;

/**
 * Modules\User\Models\Phone
 *
 * @property-read string $id
 * @property string      $area_code
 * @property string      $phone
 * @property string      $formatted
 * @property string      $international
 * @property string      $full_number
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\Phone newModelQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\Phone newQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\Phone query()
 * @mixin \Eloquent
 */
class Phone extends BaseModel
{
    public $timestamps = FALSE;

    protected $fillable = [
        'area_code',
        'phone',
    ];

    /**
     * @return string
     */
    public function getFormattedAttribute()
    {
        $string = "(".$this->attributes['area_code'].") ";
        $string .= substr($this->attributes['phone'], 0, 5)."-";
        $string .= substr($this->attributes['phone'], 5);

        return $string;
    }

    /**
     * @return string
     */
    public function getInternationalAttribute()
    {
        return "55".$this->attributes['area_code'].$this->attributes['phone'];
    }

    /**
     * @return string
     */
    public function getFullNumberAttribute()
    {
        return $this->attributes['area_code'].$this->attributes['phone'];
    }
}
