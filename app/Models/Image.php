<?php

namespace App\Models;

use App\Traits\FileUrl;
use Modules\Catalog\Models\Template;
use Modules\Stock\Models\Product;

/**
 * Class Image
 *
 * @package App\Models
 * @property-read string                                                              $id
 * @property-read string                                                              $template_id
 * @property string                                                                   $path
 * @property string                                                                   $basic
 * @property string                                                                   $thumbnail
 * @property string                                                                   $square
 * @property string                                                                   $name
 * @property string                                                                   $extension
 * @property string                                                                   $icon
 * @property string                                                                   $size
 * @property string                                                                   $mime_type
 * @property int                                                                      $size_in_bytes
 * @property bool                                                                     $is_processed
 * @property \Modules\Catalog\Models\Template                                         $template
 * @property \Illuminate\Database\Eloquent\Collection|\Modules\Stock\Models\Product[] $products
 * @property-read string                                                              $square_url
 * @property-read string                                                              $thumbnail_url
 * @property-read string                                                              $url
 * @property-read \Carbon\Carbon                                                      $created_at
 * @property-read \Carbon\Carbon                                                      $updated_at
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\Image newModelQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\Image newQuery()
 * @method static \Jenssegers\Mongodb\Eloquent\Builder|\App\Models\Image query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel withCacheCooldownSeconds($seconds = NULL)
 * @mixin \Eloquent
 */
class Image extends BaseModel
{
    use FileUrl;

    private const PROCESSED_STATUS = FALSE;

    protected $fillable = [
        'path',
        'basic',
        'thumbnail',
        'square',
        'name',
        'extension',
        'mime_type',
        'size',
        'size_in_bytes',
        'icon',
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
            ? $this->fileUrl($this->attributes['basic'])
            : $this->fileUrl($this->attributes['path']);
    }

    /**
     * @return string
     */
    public function getThumbnailUrlAttribute()
    {
        return (isset($this->attributes['thumbnail']))
            ? $this->fileUrl($this->attributes['thumbnail'])
            : config('image.sizes.thumbnail.placeholder');
    }

    /**
     * @return string
     */
    public function getSquareUrlAttribute()
    {
        return (isset($this->attributes['square']))
            ? $this->fileUrl($this->attributes['square'])
            : config('image.sizes.square.placeholder');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
