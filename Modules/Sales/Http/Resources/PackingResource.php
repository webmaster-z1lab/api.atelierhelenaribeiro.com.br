<?php

namespace Modules\Sales\Http\Resources;

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
        $is_closed = $this->resource->checked_out_at !== NULL;
        $products = [];
        foreach ($this->resource->products()->distinct()->get(['reference'])->pluck(['reference'])->all() as $reference) {
            /** @var \Modules\Sales\Models\Product $product */
            $product = $this->resource->products()->where('reference', $reference)->first();
            $products[] = [
                'reference' => $reference,
                'thumbnail' => $product->thumbnail,
                'size'      => $product->size,
                'color'     => $product->color,
                'price'     => floatval($product->price / 100.0),
                'amount'    => $this->resource->products()->where('reference', $reference)->count(),
            ];
        }

        return [
            'id'             => $this->resource->id,
            'status'         => !$is_closed ? 'opened' : 'closed',
            'seller_id'      => $this->resource->seller_id,
            'seller'         => EmployeeResource::make($this->resource->seller),
            'total_amount'   => $this->resource->total_amount,
            'total_price'    => $this->resource->total_price,
            'products'       => $products,
            'checked_out_at' => $this->when($is_closed, function () {
                return $this->resource->checked_out_at->format('d/m/Y H:i');
            }),
            'created_at'     => $this->resource->created_at->format('d/m/Y H:i'),
            'updated_at'     => $this->resource->updated_at->toW3cString(),
        ];
    }
}
