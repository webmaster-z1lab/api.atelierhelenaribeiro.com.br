<?php

namespace Modules\Stock\Http\Resources;

use App\Http\Resources\ImageResource;
use App\Http\Resources\PriceResource;
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
            'serial'     => $this->resource->serial,
            'color'      => $this->resource->color,
            'size'       => $this->resource->size,
            'template'   => TemplateResource::make($this->resource->template),
            'price'      => PriceResource::make($this->resource->price),
            'images'     => ImageResource::collection($this->resource->images),
            'prices'     => PriceResource::collection($this->resource->prices),
            'created_at' => $this->resource->created_at->toW3cString(),
            'updated_at' => $this->resource->updated_at->toW3cString(),
        ];
    }

    /**
     * @param  \Illuminate\Http\Request       $request
     * @param  \Illuminate\Http\JsonResponse  $response
     */
    public function withResponse($request, $response)
    {
        $response->header('ETag', md5($this->resource->updated_at));
    }
}
