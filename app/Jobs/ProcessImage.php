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
    public $image;

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

        $this->setData();
        $this->image->forceFill($images)->save();

        $this->setTemplateThumbnail();
        $this->setProductsThumbnail();
    }

    /**
     * Process image data
     */
    private function setData(): void
    {
        $this->image->size = $this->formatBytes($this->image->size_in_bytes);
        $this->image->is_processed = TRUE;
    }

    /**
     * Set the template thumbnail
     */
    private function setTemplateThumbnail(): void
    {
        $template = $this->image->template;

        if (NULL !== $template && !$template->is_processed) {
            $template->thumbnail = $this->image->thumbnail_url;
            $template->is_processed = TRUE;
            $template->save();
        }
    }

    /**
     * Set the products thumbnails
     */
    private function setProductsThumbnail(): void
    {
        $products = $this->image->products;

        if ($products->isNotEmpty()) {
            foreach ($products as $product) {
                if (!$product->is_processed) {
                    $product->thumbnail = $this->image->thumbnail_url;
                    $product->is_processed = TRUE;
                    $product->save();
                }
            }
        }
    }

    /**
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function makeFormats(): array
    {
        $path = NULL !== $this->image->template_id ? 'templates/'.$this->image->template_id : 'templates';

        $processor = new ImageProcessor($this->image);
        $processor->setPath($path);

        return $processor->process();
    }
}
