<?php

namespace App\Traits;

/**
 * Trait AggregateProducts
 *
 * @package App\Traits
 *
 * @property-read \Modules\Sales\Models\Packing|\Modules\Sales\Models\Sale $resource
 */
trait AggregateProducts
{
    protected function getProducts(): array
    {
        $products = [];
        foreach ($this->resource->products()->pluck('reference')->unique()->all() as $reference) {
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

        return $products;
    }
}
