<?php

namespace Modules\Sales\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Sales\Models\Payroll;
use Modules\Sales\Models\Product;
use Modules\Sales\Models\Refund;
use Modules\Sales\Models\Visit;
use Modules\Stock\Models\ProductStatus;

class AddProductsToPacking implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    /**
     * @var \Modules\Sales\Models\Visit
     */
    public $visit;

    /**
     * @var bool
     */
    public $is_payroll;

    /**
     * Create a new job instance.
     *
     * @param  \Modules\Sales\Models\Visit  $visit
     * @param  bool                         $is_payroll
     */
    public function __construct(Visit $visit, bool $is_payroll)
    {
        $this->visit = $visit->fresh();
        $this->is_payroll = $is_payroll;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->is_payroll) {
            $refunds = Refund::where('visit_id', $this->visit->id)->get();
        } else {
            $refunds = Payroll::where('completion_visit_id', $this->visit->id)->where('status', ProductStatus::RETURNED_STATUS)->get();
        }

        $products = $refunds->map(function ($refund) {
            /** @var \Modules\Sales\Models\Refund|\Modules\Sales\Models\Payroll $refund */
            return new Product([
                'product_id' => $refund->product_id,
                'reference'  => $refund->reference,
                'thumbnail'  => $refund->thumbnail,
                'size'       => $refund->size,
                'color'      => $refund->color,
                'price'      => $refund->price,
                'status'     => ProductStatus::RETURNED_STATUS,
            ]);
        });

        $packing = $this->visit->packing;

        $products->each(function (Product $product) use (&$packing) {
            $packing->products()->associate($product);
        });

        $packing->save();

        \Modules\Stock\Models\Product::whereKey($products->pluck('product_id')->all())->update(['status' => ProductStatus::RETURNED_STATUS]);

    }
}
