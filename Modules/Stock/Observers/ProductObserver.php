<?php

namespace Modules\Stock\Observers;

use Modules\Stock\Models\Product;

class ProductObserver
{
    /**
     * @param  \Modules\Stock\Models\Product  $product
     */
    public function creating(Product $product): void
    {
        $product->thumbnail = config('image.sizes.thumbnail.placeholder');
    }
}
