<?php

namespace Modules\Stock\Repositories;

use App\Models\Price;
use App\Repositories\ImageRepository;
use App\Traits\FileUpload;
use Modules\Catalog\Models\Template;
use Modules\Stock\Models\Color;
use Modules\Stock\Models\Product;

class ProductRepository
{
    use FileUpload;

    /**
     * @param  int   $items
     * @param  bool  $paginate
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection|\Modules\Stock\Models\Product[]
     */
    public function all(int $items = 10, bool $paginate = TRUE)
    {
        if (!empty(\Request::query()) && NULL !== \Request::query()['search']) return $this->search();

        return Product::latest()->take(30)->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Modules\Stock\Models\Product[]
     */
    public function search()
    {
        $query = \Request::query()['search'];

        return Product::all();
    }

    /**
     * @param  array  $data
     *
     * @return \Illuminate\Support\Collection
     */
    public function create(array $data)
    {
        $data['amount'] = intval($data['amount']);

        $products = collect();
        $template = Template::find($data['template']);

        for($i = 0; $i < $data['amount']; $i++) {
            $data['barcode'] = \Str::random();

            $product = new Product($data);

            $product->template()->associate($template);
            $product->color()->associate($this->createColor($data['color']));

            if (array_key_exists('price', $data) && filled($data['price'])) {
                $product->prices()->associate($this->createPrice(intval($data['price'])));
            } else {
                $product->prices()->associate($this->createPrice($template->price->price));
            }

            $product->save();
            $product->images()->saveMany($this->createImages($data['images']));

            $products->add($product);
        }

        return $products;
    }

    /**
     * @param  array                          $data
     * @param  \Modules\Stock\Models\Product  $product
     *
     * @return \Modules\Stock\Models\Product
     */
    public function update(array $data, Product $product): Product
    {
        if (array_key_exists('price', $data) && filled($data['price']) && $product->price->price !== intval($data['price'])) {
            $product->prices()->associate($this->createPrice(intval($data['price'])));
        }

        if ($product->color->name !== $data['color']) {
            $product->color()->associate($this->createColor($data['color']));
        }

        $product->update($data);

        if (array_key_exists('images', $data) && filled($data['images'])) {
            $product->images()->saveMany($this->createImages($data['images']));
        }

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
     * @return array
     */
    private function createImages(array $data): array
    {
        return (new ImageRepository())->createMany($data);
    }

    /**
     * @param  int  $price
     *
     * @return \App\Models\Price
     */
    private function createPrice(int $price): Price
    {
        return new Price([
            'price'      => $price,
            'started_at' => now(),
        ]);
    }

    private function createColor(string $color): Color
    {
        return new Color([
            'name' => $color,
        ]);
    }
}
