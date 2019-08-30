<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 29/07/2019
 * Time: 19:17
 */

namespace Modules\Catalog\Repositories;

use App\Models\Price;
use App\Repositories\ImageRepository;
use App\Traits\FileUpload;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Modules\Catalog\Models\Template;

class TemplateRepository
{
    use FileUpload;

    /**
     * @param  bool  $paginate
     * @param  int   $items
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection|\Modules\Catalog\Models\Template[]
     */
    public function all(bool $paginate = TRUE, int $items = 10)
    {
        if (!empty(\Request::query()) && NULL !== \Request::query()['search']) return $this->search();

        return Template::take(30)->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function search()
    {
        $query = \Request::query()['search'];

        return Template::where('reference', $query)->get();
    }

    /**
     * @param  array  $data
     *
     * @return \Modules\Catalog\Models\Template
     */
    public function create(array $data): Template
    {
        $template = new Template($data);

        $template->prices()->associate($this->createPrice($data))->save();
        $this->createImages($data, $template);

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
        $template->prices()->associate($this->createPrice($data));

        if (isset($data['images'])) $this->createImages($data, $template);

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
        abort_if($template->products()->count() > 0,400, 'Já existem produtos vinculados ao modelo.');

        $template->images()->delete();

        return $template->delete();
    }

    /**
     * @return string
     */
    public function getNewReference(): string
    {
        do {
            $reference = strtoupper(Str::random(Template::REFERENCE_LENGTH));
        } while (Template::where('reference', $reference)->count() > 0);

        return $reference;
    }

    /**
     * @param  array  $data
     *
     * @return \App\Models\Price
     */
    private function createPrice(array $data): Price
    {
        $price = new Price();

        $price->price = $data['price'];
        $price->started_at = Carbon::now();

        return $price;
    }

    /**
     * @param  array                             $data
     * @param  \Modules\Catalog\Models\Template  $template
     *
     * @return array
     */
    public function createImages(array $data, Template $template)
    {
        return (new ImageRepository)->createMany($data['images'], $template);
    }
}
