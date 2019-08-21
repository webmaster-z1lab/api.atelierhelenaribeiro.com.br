<?php

namespace App\Jobs;

use App\Models\Image;
use App\Services\ImageProcessor;
use App\Traits\FormatBytes;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, FormatBytes;
    /**
     * @var \App\Models\Image
     */
    protected $image;

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
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $images = $this->makeFormats();

        $params = [
            'icon' => config('icons.files.image'),
            'size' => $this->formatBytes($this->image->size_in_bytes),
        ];

        $this->image->forceFill($params + $images)->save();
    }

    /**
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function makeFormats(): array
    {
        $processor = new ImageProcessor();

        $path = 'templates/'.$this->image->template_id;
        $processor->setPath($path)->setSource($this->image->path);

        return $processor->process();
    }
}
