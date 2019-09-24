<?php

namespace Modules\Sales\Http\Resources;

use App\Traits\AggregateProducts;
use App\Traits\ResourceResponseHeaders;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Customer\Http\Resources\CustomerResource;
use Modules\Employee\Http\Resources\EmployeeResource;
use Modules\Sales\Models\Payroll;
use Modules\Sales\Models\Sale;
use Modules\Stock\Models\ProductStatus;

/**
 * Class VisitResource
 *
 * @package Modules\Sales\Http\Resources
 *
 * @property-read \Modules\Sales\Models\Visit $resource
 */
class VisitResource extends Resource
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
            'id'              => $this->resource->id,
            'status'          => $this->resource->status,
            'annotations'     => $this->resource->annotations,
            'date'            => $this->resource->date->format('d/m/Y'),
            'seller_id'       => $this->resource->seller_id,
            'seller'          => EmployeeResource::make($this->resource->seller),
            'customer_id'     => $this->resource->customer_id,
            'customer'        => CustomerResource::make($this->resource->customer),
            'customer_credit' => $this->resource->customer->credit_float ?? 0.0,
            'total_amount'    => $this->resource->total_amount,
            'discount'        => $this->resource->discount_float,
            'total_price'     => $this->resource->total_price_float,
            'sale'            => InformationResource::make($this->resource->sale),
            'sales'           => ProductResource::collection($this->getSales($this->resource->id)),
            'payroll'         => InformationResource::make($this->resource->payroll),
            'payrolls'        => ProductResource::collection($this->getPayrolls($this->resource->id)),
            'payroll_sale'    => InformationResource::make($this->resource->payroll_sale),
            'payroll_sales'   => ProductResource::collection($this->getPayrollSales($this->resource->id)),
            'created_at'      => $this->resource->created_at->toW3cString(),
            'updated_at'      => $this->resource->updated_at->toW3cString(),
        ];
    }

    /**
     * @param  string  $visit_id
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getSales(string $visit_id)
    {
        return $this->aggregateProductsByVisit(Sale::class, [
            'visit_id' => $visit_id,
            '$or'      => [['deleted_at' => ['$exists' => FALSE]], ['deleted_at' => NULL]],
        ]);
    }

    /**
     * @param  string  $visit_id
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getPayrolls(string $visit_id)
    {
        return $this->aggregateProductsByVisit(Payroll::class, [
            'visit_id' => $visit_id,
            '$or'      => [['deleted_at' => ['$exists' => FALSE]], ['deleted_at' => NULL]],
        ]);
    }

    /**
     * @param  string  $visit_id
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getPayrollSales(string $visit_id)
    {
        return $this->aggregateProductsByVisit(Payroll::class, [
            'completion_visit_id' => $visit_id,
            'status'              => ProductStatus::SOLD_STATUS,
            '$or'                 => [['deleted_at' => ['$exists' => FALSE]], ['deleted_at' => NULL]],
        ]);
    }
}
