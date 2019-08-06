<?php

namespace Modules\Customer\Http\Resources;

use App\Http\Resources\AddressResource;
use App\Http\Resources\PhoneResource;
use App\Traits\ResourceResponseHeaders;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class CustomerResource
 *
 * @package Modules\Customer\Http\Resources
 *
 * @property-read \Modules\Customer\Models\Customer $resource
 */
class CustomerResource extends Resource
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
            'id'                     => $this->resource->id,
            'company_name'           => $this->resource->company_name,
            'trading_name'           => $this->resource->trading_name,
            'document'               => $this->resource->document,
            'state_registration'     => $this->resource->state_registration,
            'municipal_registration' => $this->resource->municipal_registration,
            'email'                  => $this->resource->email,
            'address'                => AddressResource::make($this->resource->address),
            'phones'                 => PhoneResource::collection($this->resource->phones),
            'owners'                 => $this->when(NULL !== $this->resource->owners, OwnerResource::collection($this->resource->owners)),
            'created_at'             => $this->resource->created_at->toW3cString(),
            'updated_at'             => $this->resource->updated_at->toW3cString(),
        ];
    }
}
