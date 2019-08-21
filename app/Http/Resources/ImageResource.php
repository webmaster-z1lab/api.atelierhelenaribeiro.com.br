<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ImageResource
 *
 * @package App\Http\Resources
 *
 * @property-read \App\Models\Image $resource
 */
class ImageResource extends JsonResource
{
    /**
     * @var string
     */
    protected $product_id;

    /**
     * ImageResource constructor.
     *
     * @param               $resource
     * @param  string|NULL  $product_id
     */
    public function __construct($resource, string $product_id = NULL)
    {
        parent::__construct($resource);
        $this->product_id = $product_id;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->resource->id,
            'template_id'   => $this->resource->template_id,
            'product_id'    => $this->when($this->product_id !== NULL, $this->product_id),
            'name'          => $this->resource->name,
            'extension'     => $this->resource->extension,
            'path'          => $this->resource->path,
            'url'           => $this->resource->url,
            'square_url'    => $this->resource->square_url,
            'thumbnail_url' => $this->resource->thumbnail_url,
            'icon'          => $this->resource->icon,
            'size'          => $this->resource->size,
            'size_in_bytes' => $this->resource->size_in_bytes,
            'mime_type'     => $this->resource->mime_type,
        ];
    }
}
