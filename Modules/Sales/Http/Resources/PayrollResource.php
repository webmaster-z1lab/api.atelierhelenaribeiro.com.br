<?php

namespace Modules\Sales\Http\Resources;

use App\Traits\ResourceResponseHeaders;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Customer\Http\Resources\CustomerResource;
use Modules\Employee\Http\Resources\EmployeeResource;

/**
 * Class PayrollResource
 *
 * @package Modules\Sales\Http\Resources
 *
 * @property-read \Modules\Sales\Models\Payroll $resource
 */
class PayrollResource extends Resource
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
            'status'      => $this->resource->status,
            'date'        => $this->resource->date->format('d/m/Y'),
            'visit_id'    => $this->resource->visit_id,
            'seller_id'   => $this->resource->seller_id,
            'seller'      => EmployeeResource::make($this->resource->seller),
            'customer_id' => $this->resource->customer_id,
            'customer'    => CustomerResource::make($this->resource->customer),
            'price'       => $this->resource->price_float,
            'reference'   => $this->resource->reference,
            'thumbnail'   => $this->resource->thumbnail_url,
            'size'        => $this->resource->size,
            'color'       => $this->resource->color,
            'product_id'  => $this->resource->product_id,
            $this->mergeWhen(!is_null($this->resource->completion_date), function () {
                return [
                    'completion_date'     => $this->resource->completion_date->toW3cString(),
                    'completion_visit_id' => $this->resource->completion_visit_id,
                ];
            }),
            'created_at'  => $this->resource->created_at->toW3cString(),
            'updated_at'  => $this->resource->updated_at->toW3cString(),
        ];
    }
}
