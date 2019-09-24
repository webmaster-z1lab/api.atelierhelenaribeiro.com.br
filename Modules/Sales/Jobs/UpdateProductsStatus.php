<?php

namespace Modules\Sales\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Sales\Models\Packing;

class UpdateProductsStatus implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels, \App\Traits\UpdateProductsStatus;

    /**
     * @var \Modules\Sales\Models\Packing
     */
    public $packing;

    /**
     * @var array
     */
    public $products;

    /**
     * @var string
     */
    public $status;

    /**
     * @var bool
     */
    private $update_packing;

    /**
     * Create a new job instance.
     *
     * @param  \Modules\Sales\Models\Packing  $packing
     * @param  array                          $products
     * @param  string                         $status
     * @param  bool                           $update_packing
     */
    private function __construct(Packing $packing, array $products, string $status, bool $update_packing = TRUE)
    {
        $this->packing = $packing->fresh();
        $this->products = $products;
        $this->status = $status;
        $this->update_packing = $update_packing;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->updateStatus($this->packing, $this->products, $this->status, $this->update_packing);
    }
}
