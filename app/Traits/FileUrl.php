<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 21/08/2019
 * Time: 16:09
 */

namespace App\Traits;


use Illuminate\Support\Facades\Storage;

trait FileUrl
{
    /**
     * Set the time in minutes for the url expiration
     *
     * @var int
     */
    protected $ttl = 30;

    /**
     * @param  string  $path
     *
     * @return string
     */
    public function fileUrl(string $path): string
    {
        if(config('filesystems.default') === 's3') return $this->temporaryFileUrl($path);

        return Storage::url($path);
    }

    /**
     * @param  string  $path
     *
     * @return string
     */
    private function temporaryFileUrl(string $path): string
    {
        return Storage::temporaryUrl($path, now()->addMinutes($this->ttl));
    }
}
