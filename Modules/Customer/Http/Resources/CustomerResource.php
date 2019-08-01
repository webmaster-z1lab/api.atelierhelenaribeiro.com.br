<?php

namespace Modules\Customer\Http\Resources;

use App\Http\Resources\AddressResource;
use App\Http\Resources\PhoneResource;
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
            'contacts'               => ContactResource::collection($this->resource->contacts),
            'owners'                 => $this->when($this->resource->owners()->exists(), function () {
                return OwnerResource::collection($this->resource->owners);
            }),
            'created_at'             => $this->resource->created_at->toW3cString(),
            'updated_at'             => $this->resource->updated_at->toW3cString(),
        ];
    }

    /**
     * @param  \Illuminate\Http\Request       $request
     * @param  \Illuminate\Http\JsonResponse  $response
     */
    public function withResponse($request, $response)
    {
        $response->header('ETag', md5($this->resource->updated_at));
    }
}
