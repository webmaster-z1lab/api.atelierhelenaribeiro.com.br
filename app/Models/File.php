<?php

namespace App\Models;

/**
 * Class File
 *
 * @package App\Models
 * @property-read string         $id
 * @property string              $name
 * @property string              $extension
 * @property int                 $size_in_bytes
 * @property string              $size
 * @property string              $path
 * @property string              $icon
 * @property string              $visibility
 * @property-read string         $url
 * @property-read \Carbon\Carbon $created_at
 * @property-read \Carbon\Carbon $updated_at
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\File newModelQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\File newQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\File query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = NULL)
 * @mixin \Eloquent
 */
class File extends BaseModel
{
    protected $fillable = [
        'name',
        'extension',
        'path',
        'size_in_bytes',
        'visibility',
    ];

    /**
     * @return string
     */
    public function getUrlAttribute()
    {
        return \Storage::url($this->attributes['path']);
    }
}
