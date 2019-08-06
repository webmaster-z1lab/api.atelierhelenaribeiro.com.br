<?php

namespace Modules\Employee\Http\Resources;

use App\Http\Resources\AddressResource;
use App\Http\Resources\PhoneResource;
use App\Traits\ResourceResponseHeaders;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class EmployeeResource
 *
 * @package Modules\Employee\Http\Resources
 *
 * @property-read \Modules\User\Models\User $resource
 */
class EmployeeResource extends Resource
{
    use ResourceResponseHeaders;

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
            'name'        => $this->resource->name,
            'document'    => $this->resource->document,
            'email'       => $this->resource->email,
            'type'        => $this->resource->type,
            'created_at'  => $this->resource->created_at->toW3cString(),
            'updated_at'  => $this->resource->updated_at->toW3cString(),
            'address'     => $this->when(NULL !== $this->resource->address, AddressResource::make($this->resource->address)),
            'phone'       => $this->when(NULL !== $this->resource->phone, PhoneResource::make($this->resource->phone)),
        ];
    }
}
