<?php

namespace Modules\Order\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Stock\Models\Product;
use Modules\Stock\Models\ProductStatus;

class ShipProducts implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    /**
     * @var array
     */
    public $products;

    /**
     * Create a new job instance.
     *
     * @param  array  $products
     */
    public function __construct(array $products)
    {
        $this->products = $products;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Product::whereKey($this->products)->update(['status' => ProductStatus::SHIPPED_STATUS]);
    }
}
