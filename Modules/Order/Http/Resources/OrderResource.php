<?php

namespace Modules\Order\Http\Resources;

use App\Traits\ResourceResponseHeaders;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Customer\Http\Resources\CustomerResource;
use Modules\Order\Models\Order;
use Modules\Stock\Http\Resources\ProductResource;

/**
 * Class OrderResource
 *
 * @package Modules\Order\Http\Resources
 *
 * @property-read \Modules\Order\Models\Order $resource
 */
class OrderResource extends Resource
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
            'id'                => $this->resource->id,
            'status'            => $this->resource->status,
            'annotations'       => $this->resource->annotations,
            'tracking_code'     => $this->resource->tracking_code,
            'freight'           => $this->resource->freight_float,
            'total_price'       => $this->resource->total_price_float,
            'event_date'        => $this->resource->event_date->format('d/m/Y'),
            'ship_until'        => $this->resource->ship_until->format('d/m/Y'),
            'shipped_at'        => $this->when($this->resource->status === Order::SHIPPED_STATUS, function () {
                return $this->resource->shipped_at->format('d/m/Y');
            }),
            'customer'          => CustomerResource::make($this->resource->customer),
            'customer_id'       => $this->resource->customer_id,
            'products'          => ProductResource::collection($this->resource->products),
            'created_at'        => $this->resource->created_at->toW3cString(),
            'updated_at'        => $this->resource->updated_at->toW3cString(),
        ];
    }
}
