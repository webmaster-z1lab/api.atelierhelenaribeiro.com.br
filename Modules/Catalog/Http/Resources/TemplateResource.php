<?php

namespace Modules\Catalog\Http\Resources;

use App\Http\Resources\ImageResource;
use App\Http\Resources\PriceResource;
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
            'created_at' => $this->resource->created_at->toW3cString(),
            'updated_at' => $this->resource->updated_at->toW3cString(),
            'images'     => ImageResource::collection($this->resource->images),
            'prices'     => PriceResource::collection($this->resource->prices),
            'price'      => PriceResource::make($this->resource->price),
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