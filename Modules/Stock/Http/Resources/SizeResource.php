<?php

namespace Modules\Stock\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class SizeResource
 *
 * @package Modules\Stock\Http\Resources
 *
 * @property-read \Modules\Stock\Models\Size $resource
 */
class SizeResource extends Resource
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
            'id'        => $this->resource->id,
            'name'      => $this->resource->name,
            'reference' => $this->resource->reference,
        ];
    }
}
