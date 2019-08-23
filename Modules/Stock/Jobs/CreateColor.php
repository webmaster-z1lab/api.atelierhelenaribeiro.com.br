<?php

namespace Modules\Stock\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Stock\Models\Color;

class CreateColor implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;
    /**
     * @var string
     */
    public $color;

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
     */
    public function handle(): void
    {
        if (!Color::where('name', $this->color)->exists()) Color::create(['name' => $this->color]);
    }
}
