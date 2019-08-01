<?php

namespace Modules\Customer\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class ContactResource
 *
 * @package Modules\Customer\Http\Resources
 *
 * @property-read \Modules\Customer\Models\Contact $resource
 */
class ContactResource extends Resource
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
            'created_at' => $this->resource->created_at->toW3cString(),
            'updated_at' => $this->resource->updated_at->toW3cString(),
        ];
    }
}
