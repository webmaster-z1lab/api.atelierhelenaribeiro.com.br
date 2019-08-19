<?php

namespace App\Jobs;

use App\Models\Image;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var \App\Models\Image
     */
    protected $image;

    private $types = [
        'cover',
        'square',
        'thumbnail',
    ];

    /**
     * ProcessImage constructor.
     *
     * @param  \App\Models\Image  $image
     */
    public function __construct(Image $image)
    {
        $this->image = $image->fresh();
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
