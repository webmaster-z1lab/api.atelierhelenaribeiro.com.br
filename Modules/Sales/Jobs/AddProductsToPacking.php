<?php

namespace Modules\Sales\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Sales\Models\Packing;
use Modules\Sales\Models\Product;
use Modules\Stock\Models\ProductStatus;

class AddProductsToPacking implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    /**
     * @var \Modules\Sales\Models\Packing
     */
    public $packing;

    /**
     * @var array
     */
    private $products;

    /**
     * Create a new job instance.
     *
     * @param  \Modules\Sales\Models\Packing  $packing
     * @param  array                          $products
     */
    public function __construct(Packing $packing, array $products)
    {
        $this->packing = $packing->fresh();
        $this->products = $products;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->products as $product) {
            $this->packing->products()->associate(new Product([
                'product_id' => $product['product_id'],
                'reference'  => $product['reference'],
                'thumbnail'  => $product['thumbnail'],
                'size'       => $product['size'],
                'color'      => $product['color'],
                'price'      => $product['price'],
                'status'     => ProductStatus::RETURNED_STATUS,
            ]));
        }

        $this->packing->save();

        \Modules\Stock\Models\Product::whereKey(\Arr::pluck($this->products, 'product_id'))->update(['status' => ProductStatus::RETURNED_STATUS]);

    }
}
