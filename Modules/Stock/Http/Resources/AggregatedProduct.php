<?php

namespace Modules\Stock\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Catalog\Http\Resources\TemplateResource;
use Modules\Catalog\Models\Template;

class AggregatedProduct extends Resource
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
            'template' => TemplateResource::make(Template::find($this->resource->id->template)),
            'amount'   => $this->resource->count,
            $this->mergeWhen($request->filled('template'), function () {
                return [
                    'size'     => $this->resource->id->size,
                    'color'    => $this->resource->id->color,
                    'products' => BasicProduct::collection(collect($this->resource->products->bsonSerialize())),
                ];
            }),
        ];
    }
}
