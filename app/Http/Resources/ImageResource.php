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
            'name'          => $this->resource->name,
            'extension'     => $this->resource->extension,
            'path'          => $this->resource->path,
            'icon'          => $this->resource->icon,
            'size'          => $this->resource->size,
            'size_in_bytes' => $this->resource->size_in_bytes,
        ];
    }
}
