<?php

namespace Modules\Catalog\Http\Resources;

use App\Http\Resources\ImageResource;
use App\Http\Resources\PriceResource;
use App\Traits\ResourceResponseHeaders;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class TemplateResource
 *
 * @package Modules\Catalog\Http\Resources
 *
 * @property-read \Modules\Catalog\Models\Template resource
 */
class TemplateResource extends Resource
{
    use ResourceResponseHeaders;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->resource->id,
            'reference'  => $this->resource->reference,
            'price'      => $this->resource->price->price,
            'is_active'      => $this->resource->is_active,
            'created_at' => $this->resource->created_at->toW3cString(),
            'updated_at' => $this->resource->updated_at->toW3cString(),
            'images'     => ImageResource::collection($this->resource->images),
            'prices'     => PriceResource::collection($this->resource->prices),

        ];
    }
}
