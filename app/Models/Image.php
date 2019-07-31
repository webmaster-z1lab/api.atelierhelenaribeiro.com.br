<?php

namespace App\Models;

/**
 * Class Image
 *
 * @package App\Models
 * @property string              $path
 * @property string              $basic
 * @property string              $thumbnail
 * @property string              $square
 * @property string              $name
 * @property string              $extension
 * @property int                 $size_in_bytes
 * @property bool                $is_processed
 * @property-read \Carbon\Carbon $created_at
 * @property-read \Carbon\Carbon $updated_at
 * @property-read string         $id
 * @property-read string         $square_url
 * @property-read string         $thumbnail_url
 * @property-read string         $url
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\Image newModelQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\Image newQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\Image query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = NULL)
 * @mixin \Eloquent
 */
class Image extends BaseModel
{
    private const PROCESSED_STATUS = FALSE;

    protected $fillable = [
        'path',
        'basic',
        'thumbnail',
        'square',
        'name',
        'extension',
        'size_in_bytes',
        'is_processed',
    ];

    protected $attributes = [
        'is_processed' => self::PROCESSED_STATUS,
    ];

    /**
     * @return string
     */
    public function getUrlAttribute()
    {
        return (isset($this->attributes['basic']))
            ? \Storage::url($this->attributes['basic'])
            : \Storage::url($this->attributes['path']);
    }

    /**
     * @return string
     */
    public function getThumbnailUrlAttribute()
    {
        return (isset($this->attributes['thumbnail']))
            ? \Storage::url($this->attributes['thumbnail'])
            : NULL;
    }

    /**
     * @return string
     */
    public function getSquareUrlAttribute()
    {
        return (isset($this->attributes['square']))
            ? \Storage::url($this->attributes['square'])
            : NULL;
    }
}
