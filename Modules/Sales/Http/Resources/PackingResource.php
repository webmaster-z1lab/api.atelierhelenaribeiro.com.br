<?php

namespace Modules\Sales\Http\Resources;

use App\Traits\AggregateProducts;
use App\Traits\ResourceResponseHeaders;
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
    use ResourceResponseHeaders, AggregateProducts;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        if (!is_null($this->resource->checked_out_at)) {
            $status = 'closed';
        } elseif ($this->resource->visits()->exists()) {
            $status = 'in_transit';
        } else {
            $status = 'opened';
        }

        return [
            'id'             => $this->resource->id,
            'status'         => $status,
            'seller_id'      => $this->resource->seller_id,
            'seller'         => EmployeeResource::make($this->resource->seller),
            'total_amount'   => $this->resource->total_amount,
            'total_price'    => $this->resource->total_price,
            'products'       => $this->getProducts($request->route()->getName() !== 'packings.current'),
            'checked_out_at' => $this->when($status === 'closed', function () {
                return $this->resource->checked_out_at->toW3cString();
            }),
            'created_at'     => $this->resource->created_at->toW3cString(),
            'updated_at'     => $this->resource->updated_at->toW3cString(),
        ];
    }
}
