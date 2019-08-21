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
     * @param  array  $data
     *
     * @return \App\Models\Image
     */
    public function create(array $data): Image
    {
        return new Image($data);
    }

    /**
     * @param  array  $data
     *
     * @return array
     */
    public function createMany(array $data): array
    {
        $images = [];

        foreach ($data as $image) {
            $images[] = $this->create($image);
        }

        return $images;
    }

    /**
     * @param $data
     *
     * @return \App\Models\Image
     */
    public function createBase64($data): Image
    {
        $file = $this->uploadImageBase64($data, 'images');

        return new Image($file);
    }

    /**
     * @param  array  $data
     *
     * @return array
     */
    public function createManyBase64(array $data): array
    {
        $images = [];

        foreach ($data as $image) {
            $images[] = $this->createBase64($image);
        }

        return $images;
    }

}
