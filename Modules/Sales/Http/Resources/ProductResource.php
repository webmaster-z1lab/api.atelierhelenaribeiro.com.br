<?php

namespace Modules\Sales\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

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
            'barcode'    => $this->resource->barcode,
            'thumbnail'  => $this->resource->thumbnail,
            'size'       => $this->resource->size,
            'color'      => $this->resource->color,
            'price'      => $this->resource->price,
            'status'     => $this->resource->status,
        ];
    }
}
