<?php

namespace Modules\Employee\Http\Resources;

use App\Http\Resources\AddressResource;
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
            'phone'       => NULL !== $this->resource->phone ? $this->resource->phone->full_number : NULL,
            'is_whatsapp' => NULL !== $this->resource->phone ? $this->resource->phone->is_whatsapp : NULL,
        ];
    }
}
