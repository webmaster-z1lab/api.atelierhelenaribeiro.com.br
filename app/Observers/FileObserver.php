<?php

namespace App\Observers;

use App\Jobs\ProcessFile;
use App\Models\File;

class FileObserver
{
    /**
     * @param  \App\Models\File  $file
     */
    public function creating(File $file)
    {
        $file->icon = config('icons.files.default');
        $file->size = "$file->size_in_bytes bytes";
    }

    /**
     * @param  \App\Models\File  $file
     */
    public function created(File $file)
    {
        ProcessFile::dispatch($file)->delay(now()->addMinute());
    }

    /**
     * @param  \App\Models\File  $file
     */
    public function deleted(File $file)
    {
        \Storage::delete($file->path);
    }
}
