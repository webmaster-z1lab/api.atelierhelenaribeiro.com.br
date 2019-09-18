<?php

namespace App\Traits;

use Modules\Stock\Models\ProductStatus;

/**
 * Trait AggregateProducts
 *
 * @package App\Traits
 *
 * @property-read \Modules\Sales\Models\Packing|\Modules\Sales\Models\Sale $resource
 */
trait AggregateProducts
{
    protected function getProducts(bool $total): array
    {
        $products = [];
        foreach ($this->resource->products()->pluck('reference')->unique()->all() as $reference) {
            /** @var \Modules\Sales\Models\Product $product */
            $product = $this->resource->products()->where('reference', $reference)->first();
            if ($total) {
                $amount = $this->resource->products()->where('reference', $reference)->count();
            } else {
                $amount = $this->resource->products()->where('reference', $reference)
                    ->whereIn('status', [ProductStatus::IN_TRANSIT_STATUS, ProductStatus::RETURNED_STATUS])->count();
            }

            $products[] = [
                'reference' => $reference,
                'thumbnail' => $product->thumbnail_url,
                'size'      => $product->size,
                'color'     => $product->color,
                'price'     => floatval($product->price / 100.0),
                'amount'    => $amount,
            ];
        }

        return $products;
    }
}
