<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;

class DeleteImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \Illuminate\Support\Collection
     */
    public $album;

    /**
     * DeleteImage constructor.
     *
     * @param \Illuminate\Support\Collection $album
     */
    public function __construct($album)
    {
        $this->album = $album;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->album as $path) {
            if(NULL !== $path) Storage::delete($path);
        }
    }
}
