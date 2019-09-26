<?php

namespace App\Traits;

use Modules\Sales\Models\Packing;
use Modules\Stock\Models\Product;
use Modules\Stock\Models\ProductStatus;

trait RemoveProductsFromPacking
{
    /**
     * @param  \Modules\Sales\Models\Packing  $packing
     * @param  array                          $product_ids
     * @param  bool                           $is_payroll
     */
    protected function removeProducts(Packing $packing, array $product_ids, bool $is_payroll): void
    {
        $products = $packing->products()->whereIn('product_id', $product_ids)->get()->modelKeys();

        $packing->products()->dissociate($products);

        Product::whereKey($product_ids)->update(['status' => ($is_payroll ? ProductStatus::ON_CONSIGNMENT_STATUS : ProductStatus::SOLD_STATUS)]);
    }
}
