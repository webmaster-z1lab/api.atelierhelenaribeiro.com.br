<?php

namespace Modules\Stock\Http\Resources;

use App\Http\Resources\ImageResource;
use App\Http\Resources\PriceResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

class BasicProductResource extends Resource
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
        $price = collect($this->resource->prices->bsonSerialize())->sortByDesc('started_at')->first();


        return [
            'id'          => (string) $this->resource->_id,
            'barcode'     => $this->resource->barcode,
            'size'        => $this->resource->size,
            'color'       => $this->resource->color,
            'template_id' => $this->resource->template_id,
            'price'       => $price->price,
            'created_at'  => $this->resource->created_at->toDateTime()->format(\DateTime::W3C),
            'updated_at'  => $this->resource->updated_at->toDateTime()->format(\DateTime::W3C),
        ];
    }
}
