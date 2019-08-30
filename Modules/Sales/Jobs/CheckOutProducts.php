<?php

namespace Modules\Sales\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Sales\Models\Packing;
use Modules\Stock\Models\Product;
use Modules\Stock\Models\ProductStatus;

class CheckOutProducts implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    /**
     * @var \Modules\Sales\Models\Packing
     */
    public $packing;

    /**
     * Create a new job instance.
     *
     * @param  \Modules\Sales\Models\Packing  $packing
     */
    public function __construct(Packing $packing)
    {
        $this->packing = $packing->fresh();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $products = $this->packing->products()
            ->whereIn('status', [ProductStatus::IN_TRANSIT_STATUS, ProductStatus::RETURNED_STATUS])
            ->get(['product_id'])
            ->pluck('product_id')
            ->all();

        Product::whereIn('_id', $products)->update(['status' => ProductStatus::AVAILABLE_STATUS]);
    }
}
