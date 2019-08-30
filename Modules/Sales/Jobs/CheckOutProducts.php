<?php

namespace Modules\Sales\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Sales\Models\Packing;

class CheckOutProducts implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    /**
     * @var \Modules\Sales\Models\Packing
     */
    private $packing;

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
    }
}
