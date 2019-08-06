<?php

namespace Modules\Customer\Http\Resources;

use App\Http\Resources\PhoneResource;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class PersonResource
 *
 * @package Modules\Customer\Http\Resources
 *
 * @property-read \Modules\Customer\Models\Owner $resource
 */
class OwnerResource extends Resource
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
            'id'         => $this->resource->id,
            'name'       => $this->resource->name,
            'document'   => $this->resource->document,
            'email'      => $this->resource->email,
            'birth_date' => $this->resource->birth_date->format('d/m/Y'),
            'phones'     => PhoneResource::make($this->resource->phone),
        ];
    }
}
