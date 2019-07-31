<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class AddressResource
 *
 * @package App\Http\Resources
 *
 * @property-read \App\Models\Address $resource
 */
class AddressResource extends Resource
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
            'id'          => $this->resource->id,
            'street'      => $this->resource->street,
            'number'      => $this->resource->number,
            'complement'  => $this->resource->complement,
            'district'    => $this->resource->district,
            'postal_code' => $this->resource->postal_code,
            'city'        => $this->resource->city,
            'state'       => $this->resource->state,
            'formatted'   => $this->resource->formatted,
        ];
    }
}
