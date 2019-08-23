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
    public function created(Image $image)
    {
        ProcessImage::dispatchNow($image);
    }

    /**
     * @param  \App\Models\Image  $image
     */
    public function deleted(Image $image)
    {
        DeleteImage::dispatch($image->album);
    }
}
