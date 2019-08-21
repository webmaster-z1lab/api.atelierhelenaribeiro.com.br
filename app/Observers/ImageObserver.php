<?php

namespace App\Observers;

use App\Jobs\DeleteImage;
use App\Jobs\ProcessImage;
use App\Models\Image;

class ImageObserver
{
    public function created(Image $image)
    {
        ProcessImage::dispatchNow($image);
    }

    public function deleted(Image $image)
    {
        DeleteImage::dispatch($image->thumbnail, $image->square, $image->basic);
    }
}
