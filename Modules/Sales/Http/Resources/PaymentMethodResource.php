<?php

namespace Modules\Sales\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class PaymentMethodResource
 *
 * @package Modules\Sales\Http\Resources
 *
 * @property \Modules\Sales\Models\PaymentMethod $resource
 */
class PaymentMethodResource extends Resource
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
            'id'     => $this->resource->id,
            'method' => $this->resource->method,
            'value'  => $this->resource->value_float,
        ];
    }
}
