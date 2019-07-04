<?php

namespace App\Jobs;

use App\Models\File;
use App\Traits\FormatBytes;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, FormatBytes;

    /**
     * @var \App\Models\File
     */
    protected $file;

    protected $types;

    /**
     * ProcessFile constructor.
     *
     * @param  \App\Models\File  $file
     */
    public function __construct(File $file)
    {
        $this->file = $file->fresh();
        $this->types = collect(config('file-types'));
    }

    public function handle()
    {
        $icon = $this->getIcon();

        $this->file->size = $this->formatBytes($this->file->size_in_bytes);
        if ($icon) $this->file->icon = $icon;

        $this->file->save();
    }

    /**
     * @return bool|string
     */
    private function getIcon()
    {
        foreach ($this->types as $key => $type) {
            if (in_array($this->file->extension, $type)) {
                $aux = "icons.files.$key";
                if (config()->has($aux)) return config($aux);

                return FALSE;
            }
        }

        return FALSE;
    }
}
