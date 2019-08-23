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
use Modules\Catalog\Models\Template;

class ImageRepository
{
    use FileUpload;

    /**
     * @param  array                             $data
     * @param  \Modules\Catalog\Models\Template  $template
     *
     * @return \App\Models\Image
     */
    public function create(array $data, Template $template = NULL): Image
    {
        $image = new Image($data);
        if (NULL !== $template) $image->template()->associate($template);

        return $image;
    }

    /**
     * @param  array                             $data
     * @param  \Modules\Catalog\Models\Template  $template
     *
     * @return array
     */
    public function createMany(array $data, Template $template = NULL): array
    {
        $images = [];

        foreach ($data as $image) {
            $images[] = $this->create($image, $template);
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
