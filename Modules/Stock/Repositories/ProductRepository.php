<?php

namespace Modules\Stock\Repositories;

use App\Models\Image;
use App\Models\Price;
use Modules\Catalog\Models\Template;
use Modules\Stock\Models\Product;

class ProductRepository
{
    /**
     * @param  int   $items
     * @param  bool  $paginate
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection|\Modules\Stock\Models\Product[]
     */
    public function all(int $items = 10, bool $paginate = TRUE)
    {
        if ($paginate) return Product::paginate($items);

        return Product::all();
    }

    /**
     * @param  array  $data
     *
     * @return \Modules\Stock\Models\Product
     */
    public function create(array $data): Product
    {
        $product = new Product($data);

        $template = Template::find($data['template']);

        $product->template()->associate($template);
        if (array_key_exists('price', $data) && filled($data['price'])) {
            $product->prices()->save($this->createPrice($data));
        } else {
            $product->prices()->save($template->price);
        }

        $product->save();

        return $product;
    }

    /**
     * @param  array                          $data
     * @param  \Modules\Stock\Models\Product  $product
     *
     * @return \Modules\Stock\Models\Product
     */
    public function update(array $data, Product $product): Product
    {
        $product->template()->associate($data['template']);
        if (array_key_exists('price', $data) && filled($data['price'])) {
            $product->prices()->save($this->createPrice($data));
        }

        $product->update($data);

        return $product;
    }

    /**
     * @param  \Modules\Stock\Models\Product  $product
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Product $product)
    {
        return $product->delete();
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
