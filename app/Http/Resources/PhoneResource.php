<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class PhoneResource
 *
 * @package App\Http\Resources
 *
 * @property-read \App\Models\Phone $resource
 */
class PhoneResource extends Resource
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
            'id'            => $this->resource->id,
            'area_code'     => $this->resource->area_code,
            'phone'         => $this->resource->number,
            'international' => $this->resource->international,
            'number'        => $this->resource->full_number,
            'is_whatsapp'   => $this->resource->is_whatsapp,
            'formatted'     => $this->resource->formatted,
        ];
    }
}
