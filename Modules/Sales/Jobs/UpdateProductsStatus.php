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
     * Create a new job instance.
     *
     * @param  \Modules\Sales\Models\Packing  $packing
     * @param  array                          $products
     * @param  string                         $status
     */
    public function __construct(Packing $packing, array $products, string $status)
    {
        $this->packing = $packing->fresh();
        $this->products = $products;
        $this->status = $status;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->update($this->packing, $this->products, $this->status);
    }
}
