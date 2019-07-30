<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 29/07/2019
 * Time: 20:09
 */

namespace App\Repositories;

use App\Models\Image;

class ImageRepository
{
    /**
     * @param  bool  $paginate
     * @param  int   $items
     *
     * @return \App\Models\Image[]|\Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function all(bool $paginate = TRUE, int $items = 10)
    {
        if ($paginate) return Image::paginate($items);

        return Image::all();
    }

    /**
     * @param  array  $data
     *
     * @return \App\Models\Image|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        return Image::create($data);
    }

    /**
     * @param  array              $data
     * @param  \App\Models\Image  $image
     *
     * @return bool
     */
    public function update(array $data, Image $image)
    {
        return $image->update($data);
    }

    /**
     * @param  \App\Models\Image  $image
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Image $image)
    {
        return $image->delete();
    }
}
