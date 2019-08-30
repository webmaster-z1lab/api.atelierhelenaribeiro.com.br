<?php

namespace Modules\Sales\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Employee\Http\Resources\EmployeeResource;

/**
 * Class PackingResource
 *
 * @package Modules\Sales\Http\Resources
 *
 * @property-read \Modules\Sales\Models\Packing $resource
 */
class PackingResource extends Resource
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
        $is_closed = $this->resource->checked_out_at !== NULL;

        return [
            'id'             => $this->resource->id,
            'status'         => !$is_closed ? 'opened' : 'closed',
            'seller_id'      => $this->resource->seller_id,
            'seller'         => EmployeeResource::make($this->resource->seller),
            'products'       => ProductResource::collection($this->resource->products),
            'checked_out_at' => $this->when($is_closed, function () {
                return $this->resource->checked_out_at->toW3cString();
            }),
            'created_at'     => $this->resource->created_at->toW3cString(),
            'updated_at'     => $this->resource->updated_at->toW3cString(),
        ];
    }
}
