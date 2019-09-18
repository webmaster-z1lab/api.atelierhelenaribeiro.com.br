<?php

namespace Modules\Stock\Http\Resources;

use App\Http\Resources\ImageResource;
use App\Http\Resources\PriceResource;
use App\Traits\ResourceResponseHeaders;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Catalog\Http\Resources\TemplateResource;

/**
 * Class ProductResource
 *
 * @package Modules\Stock\Http\Resources
 *
 * @property-read \Modules\Stock\Models\Product $resource
 */
class ProductResource extends Resource
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
            'id'          => $this->resource->id,
            'barcode'     => $this->resource->barcode,
            'thumbnail'   => $this->resource->thumbnail_url,
            'size'        => $this->resource->size,
            'color'       => $this->resource->color,
            'template_id' => $this->resource->template_id,
            'template'    => TemplateResource::make($this->resource->template),
            'price'       => NULL !== $this->resource->price ? $this->resource->price->price_float : NULL,
            'images'      => $this->getImages(),
            'prices'      => PriceResource::collection($this->resource->prices),
            'created_at'  => $this->resource->created_at->toW3cString(),
            'updated_at'  => $this->resource->updated_at->toW3cString(),
        ];
    }

    /**
     * @return array
     */
    private function getImages()
    {
        $images = [];

        foreach ($this->resource->images as $image) {
            $images[] = new ImageResource($image, $this->resource->id);
        }

        return $images;
    }
}
