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
 * @property bool        $is_whatsapp
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\Phone newModelQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\Phone newQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\Phone query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = NULL)
 * @mixin \Eloquent
 */
class Phone extends BaseModel
{
    public $timestamps = FALSE;

    protected $fillable = [
        'area_code',
        'phone',
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
    public function setPhoneAttribute(string $value)
    {
        $this->attributes['area_code'] = substr($value, 0, 2);
        $this->attributes['phone'] = substr($value, 2);
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
