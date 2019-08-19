<?php

namespace Modules\Stock\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Catalog\Models\Template;

class AggregatedProductResource extends Resource
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
        $template = Template::find($this->resource->id->template);

        return [
            'template' => $template->reference,
            'amount'   => $this->resource->count,
            'size'     => $this->resource->id->size,
            'color'    => $this->resource->id->color,
            'products' => BasicProductResource::collection(collect($this->resource->products->bsonSerialize())),
        ];
    }
}
