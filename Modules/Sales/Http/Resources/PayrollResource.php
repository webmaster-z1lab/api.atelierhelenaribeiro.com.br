<?php

namespace Modules\Sales\Http\Resources;

use App\Traits\AggregateProducts;
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
        return [
            'id'           => $this->resource->id,
            'date'         => $this->resource->date->format('d/m/Y'),
            'visit_id'     => $this->resource->visit_id,
            'total_amount' => $this->resource->total_amount,
            'total_price'  => $this->resource->total_price_float,
            'seller_id'    => $this->resource->seller_id,
            'seller'       => EmployeeResource::make($this->resource->seller),
            'customer_id'  => $this->resource->customer_id,
            'customer'     => CustomerResource::make($this->resource->customer),
            'products'     => $this->getProducts(),
            'created_at'   => $this->resource->created_at->toW3cString(),
            'updated_at'   => $this->resource->updated_at->toW3cString(),

        ];
    }
}
