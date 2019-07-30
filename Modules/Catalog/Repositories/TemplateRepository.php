<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 29/07/2019
 * Time: 19:17
 */

namespace Modules\Catalog\Repositories;

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
     * @return \Illuminate\Database\Eloquent\Model|\Modules\Catalog\Models\Template
     */
    public function create(array $data)
    {
        return Template::create($data);
    }

    /**
     * @param  array                             $data
     * @param  \Modules\Catalog\Models\Template  $template
     *
     * @return bool
     */
    public function update(array $data, Template $template)
    {
        return $template->update($data);
    }

    /**
     * @param  \Modules\Catalog\Models\Template  $template
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Template $template): bool
    {
        return $template->delete();
    }

}
