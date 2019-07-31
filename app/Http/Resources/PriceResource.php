<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PriceResource
 *
 * @package App\Http\Resources
 *
 * @property-read \App\Models\Price $resource
 */
class PriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->resource->id,
            'price'      => $this->resource->price,
            'started_at' => $this->resource->started_at->toW3cString(),
        ];
    }
}
