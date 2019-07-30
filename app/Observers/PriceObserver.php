<?php

namespace App\Observers;

use App\Models\Price;

class PriceObserver
{
    /**
     * @param  \App\Models\Price  $price
     */
    public function creating(Price $price)
    {
        $price->started_at = now();
    }
}
