<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 26/06/2019
 * Time: 17:24
 */

namespace App\Traits;


trait FormatBytes
{
    /**
     * @param  int  $size
     * @param  int  $precision
     *
     * @return string
     */
    public function formatBytes(int $size, int $precision = 2): string
    {
        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = [' bytes', ' KB', ' MB', ' GB', ' TB'];

            return round(pow(1024, $base - floor($base)), $precision).$suffixes[(int) floor($base)];
        }

        return "$size bytes";
    }
}
