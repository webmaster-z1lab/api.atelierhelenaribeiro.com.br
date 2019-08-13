<?php

namespace Modules\Stock\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class BasicProduct extends Resource
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
            'id'      => (string) $this->resource->_id,
            'barcode' => $this->resource->barcode,
        ];
    }
}
