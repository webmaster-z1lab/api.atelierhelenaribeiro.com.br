<?php

namespace App\Observers;

use App\Jobs\DeleteImage;
use App\Jobs\ProcessImage;
use App\Models\Image;

class ImageObserver
{
    /**
     * @param  \App\Models\Image  $image
     */
    public function creating(Image $image): void
    {
        $image->icon = config('icons.files.image');
        $image->size = "{$image->size_in_bytes} bytes";
    }

    /**
     * @param  \App\Models\Image  $image
     */
    public function created(Image $image): void
    {
        ProcessImage::dispatchNow($image);
    }

    /**
     * @param  \App\Models\Image  $image
     */
    public function deleted(Image $image): void
    {
        DeleteImage::dispatch($image->album);
    }
}
