<?php

namespace Modules\Sales\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class InformationResource
 *
 * @package Modules\Sales\Http\Resources
 *
 * @property-read \Modules\Sales\Models\Information $resource
 */
class InformationResource extends Resource
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
            'amount' => $this->resource->amount,
            'price'  => $this->resource->price_float,
        ];
    }
}
