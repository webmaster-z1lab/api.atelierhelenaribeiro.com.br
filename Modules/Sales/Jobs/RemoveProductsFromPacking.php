<?php

namespace Modules\Sales\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Sales\Models\Packing;
use Modules\Sales\Models\Visit;

class RemoveProductsFromPacking implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels, \App\Traits\RemoveProductsFromPacking;

    /**
     * @var \Modules\Sales\Models\Packing
     */
    public $packing;

    /**
     * @var array
     */
    public $products;

    /**
     * @var bool
     */
    public $is_payroll;

    /**
     * Create a new job instance.
     *
     * @param  \Modules\Sales\Models\Packing  $packing
     * @param  array                          $products
     * @param  bool                           $is_payroll
     */
    public function __construct(Packing $packing, array $products, bool $is_payroll)
    {
        $this->packing = $packing->fresh();
        $this->products = $products;
        $this->is_payroll = $is_payroll;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->removeProducts($this->packing, $this->products, $this->is_payroll);
    }
}
