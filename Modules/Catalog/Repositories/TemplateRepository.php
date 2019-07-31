<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 29/07/2019
 * Time: 19:17
 */

namespace Modules\Catalog\Repositories;

use App\Models\Image;
use App\Models\Price;
use Modules\Catalog\Models\Template;

class TemplateRepository
{
    /**
     * @param  bool  $paginate
     * @param  int   $items
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection|\Modules\Catalog\Models\Template[]
     */
    public function all(bool $paginate = TRUE, int $items = 10)
    {
        if ($paginate) return Template::paginate($items);

        return Template::all();
    }

    /**
     * @param  array  $data
     *
     * @return \Modules\Catalog\Models\Template
     */
    public function create(array $data): Template
    {
        $template = new Template($data);

        $template->prices()->save($this->createPrice($data));

        $template->save();

        return $template;
    }

    /**
     * @param  array                             $data
     * @param  \Modules\Catalog\Models\Template  $template
     *
     * @return \Modules\Catalog\Models\Template
     */
    public function update(array $data, Template $template): Template
    {
        $template->prices()->save($this->createPrice($data));

        $template->update($data);

        return $template;
    }

    /**
     * @param  \Modules\Catalog\Models\Template  $template
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Template $template)
    {
        return $template->delete();
    }

    /**
     * @param  array  $data
     *
     * @return \App\Models\Image
     */
    private function createImage(array $data): Image
    {
        return new Image($data);
    }

    /**
     * @param  array  $data
     *
     * @return \App\Models\Price
     */
    private function createPrice(array $data): Price
    {
        return new Price($data);
    }

}
