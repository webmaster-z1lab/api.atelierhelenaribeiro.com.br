<?php

namespace Modules\Sales\Http\Resources;

use App\Traits\ResourceResponseHeaders;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Customer\Http\Resources\CustomerResource;
use Modules\Employee\Http\Resources\EmployeeResource;

/**
 * Class VisitResource
 *
 * @package Modules\Sales\Http\Resources
 *
 * @property-read \Modules\Sales\Models\Visit $resource
 */
class VisitResource extends Resource
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
            'annotations' => $this->resource->annotations,
            'date'        => $this->resource->date->format('d/m/Y'),
            'seller_id'   => $this->resource->seller_id,
            'seller'      => EmployeeResource::make($this->resource->seller),
            'customer_id' => $this->resource->customer_id,
            'customer'    => CustomerResource::make($this->resource->customer),
            $this->mergeWhen($this->resource->sale()->exists(), function () {
                return [
                    'sale' => SaleResource::make($this->resource->sale),
                ];
            }),
            $this->mergeWhen($this->resource->payroll()->exists(), function () {
                return [
                    'payroll' => PayrollResource::make($this->resource->payroll),
                ];
            }),
            'created_at'  => $this->resource->created_at->toW3cString(),
            'updated_at'  => $this->resource->updated_at->toW3cString(),
        ];
    }
}
