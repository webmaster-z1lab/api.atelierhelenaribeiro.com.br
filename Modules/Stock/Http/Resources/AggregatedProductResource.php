<?php

namespace Modules\Stock\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Catalog\Models\Template;
use Modules\Stock\Models\Color;
use Modules\Stock\Models\Size;

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
        $data = explode('-', $this->resource->id);
        /** @var \Modules\Catalog\Models\Template $template */
        $template = Template::where('reference', $data[0])->first();
        /** @var \Modules\Stock\Models\Color $color */
        $color = Color::where('reference', $data[1])->first();
        /** @var \Modules\Stock\Models\Size $size */
        $size = Size::where('reference', $data[2])->first();

        return [
            'reference' => $this->resource->id,
            'template'  => $template->reference,
            'thumbnail' => $template->thumbnail,
            'amount'    => $this->resource->count,
            'size'      => $size->name,
            'color'     => $color->name,
            'products'  => BasicProductResource::collection(collect($this->resource->products->bsonSerialize())),
        ];
    }
}
