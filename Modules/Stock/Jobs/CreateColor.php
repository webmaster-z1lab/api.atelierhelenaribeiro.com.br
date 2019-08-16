<?php

namespace Modules\Stock\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Stock\Models\Color;

class CreateColor implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;
    /**
     * @var string
     */
    private $color;

    /**
     * CreateColor constructor.
     *
     * @param  string  $color
     */
    public function __construct(string $color)
    {
        $this->color = $color;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!Color::where('name', $this->color)->exists())
            Color::create(['name' => $this->color]);
    }
}
