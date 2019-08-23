<?php

namespace Modules\Stock\Observers;

use Modules\Stock\Jobs\CreateColor;
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

    /**
     * @param  \Modules\Stock\Models\Product  $product
     */
    public function saved(Product $product): void
    {
        CreateColor::dispatch($product->color);
    }
}
