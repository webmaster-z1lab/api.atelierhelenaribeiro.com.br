<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 29/07/2019
 * Time: 20:09
 */

namespace App\Repositories;

use App\Models\Image;
use App\Traits\FileUpload;

class ImageRepository
{
    use FileUpload;

    /**
     * @param $data
     *
     * @return \App\Models\Image
     */
    public function create($data)
    {
        $file = $this->upload($data, 'images');

        return new Image($file);
    }

    /**
     * @param  array  $data
     *
     * @return array
     */
    public function createMany(array $data)
    {
        $images = [];

        foreach ($data as $image) {
            $images[] = $this->create($image);
        }

        return $images;
    }

}
