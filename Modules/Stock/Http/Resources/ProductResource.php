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
            'id'               => $this->resource->id,
            'barcode'          => $this->resource->barcode,
            'thumbnail'        => $this->resource->thumbnail_url,
            'size'             => $this->resource->size,
            'color'            => $this->resource->color,
            'template_id'      => $this->resource->template_id,
            'template'         => TemplateResource::make($this->resource->template),
            'price'            => NULL !== $this->resource->price ? $this->resource->price->price_float : NULL,
            'images'           => $this->getImages(),
            'prices'           => PriceResource::collection($this->resource->prices),
            'mannequin'        => $this->when(!is_null($this->resource->mannequin), $this->resource->mannequin),
            'removable_sleeve' => $this->when(!is_null($this->resource->removable_sleeve), $this->resource->removable_sleeve),
            'has_lace'         => $this->when(!is_null($this->resource->has_lace), $this->resource->has_lace),
            'bust'             => $this->when(!is_null($this->resource->bust), $this->resource->bust),
            'armhole'          => $this->when(!is_null($this->resource->armhole), $this->resource->armhole),
            'hip'              => $this->when(!is_null($this->resource->hip), $this->resource->hip),
            'waist'            => $this->when(!is_null($this->resource->waist), $this->resource->waist),
            'slit'             => $this->when(!is_null($this->resource->slit), $this->resource->slit),
            'body'             => $this->when(!is_null($this->resource->body), $this->resource->body),
            'skirt'            => $this->when(!is_null($this->resource->skirt), $this->resource->skirt),
            'tail'             => $this->when(!is_null($this->resource->tail), $this->resource->tail),
            'shoulders'        => $this->when(!is_null($this->resource->shoulders), $this->resource->shoulders),
            'cleavage'         => $this->when(!is_null($this->resource->cleavage), $this->resource->cleavage),
            'skirt_type'       => $this->when(!is_null($this->resource->skirt_type), $this->resource->skirt_type),
            'sleeve_model'     => $this->when(!is_null($this->resource->sleeve_model), $this->resource->sleeve_model),
            'annotations'      => $this->when(!is_null($this->resource->annotations), $this->resource->annotations),
            'created_at'       => $this->resource->created_at->toW3cString(),
            'updated_at'       => $this->resource->updated_at->toW3cString(),
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
