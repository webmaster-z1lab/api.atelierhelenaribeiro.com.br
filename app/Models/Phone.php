<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Modules\User\Models\Phone
 *
 * @property-read string $id
 * @property string      $area_code
 * @property string      $number
 * @property string      $formatted
 * @property string      $international
 * @property string      $full_number
 * @property bool        $is_whatsapp
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\Phone newModelQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\Phone newQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\Phone query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = NULL)
 * @mixin \Eloquent
 */
class Phone extends Model
{
    public $timestamps = FALSE;

    protected $fillable = [
        'area_code',
        'number',
        'is_whatsapp',
    ];

    protected $attributes = [
        'is_whatsapp' => FALSE,
    ];

    protected $casts = [
        'is_whatsapp' => 'boolean',
    ];

    /**
     * @param  string  $value
     */
    public function setNumberAttribute(string $value)
    {
        $this->attributes['area_code'] = substr($value, 0, 2);
        $this->attributes['number'] = substr($value, 2);
    }

    /**
     * @param  string  $value
     */
    public function setIsWhatsappAttribute(string $value)
    {
        if ($value === 'false') {
            $this->attributes['is_whatsapp'] = FALSE;
        } else {
            $this->attributes['is_whatsapp'] = (bool) $value;
        }
    }

    /**
     * @return string
     */
    public function getFormattedAttribute()
    {
        $divider = (strlen($this->attributes['number']) === 9) ? 5 : 4;

        $string = "(".$this->attributes['area_code'].") ";
        $string .= substr($this->attributes['number'], 0, $divider)."-";
        $string .= substr($this->attributes['number'], $divider);

        return $string;
    }

    /**
     * @return string
     */
    public function getInternationalAttribute()
    {
        return "55".$this->attributes['area_code'].$this->attributes['number'];
    }

    /**
     * @return string
     */
    public function getFullNumberAttribute()
    {
        return $this->attributes['area_code'].$this->attributes['number'];
    }
}
