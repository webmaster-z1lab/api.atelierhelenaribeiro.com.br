<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 21/08/2019
 * Time: 16:09
 */

namespace App\Traits;


use Illuminate\Support\Facades\Storage;

trait S3FileUrl
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
    public function temporaryFileUrl(string $path): string
    {
        return Storage::temporaryUrl($path, now()->addMinutes($this->ttl));
    }
}
