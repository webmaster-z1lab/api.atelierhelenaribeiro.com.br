<?php

namespace App\Traits;

use Modules\Sales\Models\Packing;
use Modules\Stock\Models\Product;

trait UpdateProductsStatus
{
    /**
     * @param  \Modules\Sales\Models\Packing  $packing
     * @param  array                          $products
     * @param  string                         $status
     * @param  bool                           $update_packing
     */
    public function updateStatus(Packing $packing, array $products, string $status, bool $update_packing = TRUE)
    {
        if ($update_packing) {
            $packing->products()->whereIn('product_id', $products)->each(function (\Modules\Sales\Models\Product $product, int $key) use ($packing, $status) {
                $product->forceFill(['status' => $status]);
                $packing->products()->associate($product);
            });

            $packing->save();
        }

        Product::whereIn('_id', $products)->update(['status' => $status]);
    }
}
