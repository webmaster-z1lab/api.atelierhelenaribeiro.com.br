<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class Phone
 *
 * @package Modules\User\Http\Resources\v1
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
            'phone'         => $this->resource->phone,
            'international' => $this->resource->international,
            'full_number'   => $this->resource->full_number,
            'is_whatsapp'   => $this->resource->is_whatsapp,
            'formatted'     => $this->resource->formatted,
        ];
    }
}
