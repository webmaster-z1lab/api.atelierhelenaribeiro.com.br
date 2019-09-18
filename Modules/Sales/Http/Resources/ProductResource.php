<?php

namespace Modules\Sales\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class ProductResource
 *
 * @package Modules\Sales\Http\Resources
 *
 * @property-read \Modules\Sales\Models\Product $resource
 */
class ProductResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->resource->id,
            'product_id' => $this->resource->product_id,
            'reference'  => $this->resource->reference,
            'thumbnail'  => $this->resource->thumbnail_url,
            'size'       => $this->resource->size,
            'color'      => $this->resource->color,
            'price'      => $this->resource->price,
            'status'     => $this->resource->status,
        ];
    }
}
