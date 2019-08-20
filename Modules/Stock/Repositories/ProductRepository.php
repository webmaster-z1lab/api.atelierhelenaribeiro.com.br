<?php

namespace Modules\Stock\Repositories;

use App\Models\Image;
use App\Models\Price;
use App\Traits\FileUpload;
use Modules\Catalog\Models\Template;
use Modules\Stock\Jobs\CreateColor;
use Modules\Stock\Models\Product;

class ProductRepository
{
    use FileUpload;

    /**
     * @param  int   $items
     * @param  bool  $paginate
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Expression|\Modules\Stock\Models\Product[]
     */
    public function all(int $items = 10, bool $paginate = TRUE)
    {
        if (\Request::filled('search')) return $this->search();

        return Product::raw(function ($collection) {
            return $collection->aggregate([
                [
                    '$group' => [
                        '_id'      => ['template' => '$template_id', 'size' => '$size', 'color' => '$color'],
                        'count'    => ['$sum' => 1],
                        'products' => ['$push' => '$$ROOT'],
                    ],
                ],
                ['$limit' => 30],
            ]);
        });
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

            if (array_key_exists('price', $data) && filled($data['price'])) {
                $product->prices()->associate($this->createPrice(intval($data['price'])));
            } else {
                $product->prices()->associate($this->createPrice($template->price->price));
            }

            $product->save();
            if (array_key_exists('images', $data) && filled($data['images'])) {
                $images = $product->images()->saveMany($this->createImages($data['images']));
                foreach ($images as $image) {
                    /** @var \App\Models\Image $image */
                    $image->template()->associate($template);
                }
            } else {
                $template->images->each(function ($item, $key) use ($product) {
                    /** @var \App\Models\Image $item */
                    $item->product()->attach($product);
                });
            }

            $products->add($product);
        }

        CreateColor::dispatch($data['color']);

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

        $product->update($data);

        if (array_key_exists('images', $data) && filled($data['images'])) {
            $product->images()->saveMany($this->createImages($data['images']));
        }

        CreateColor::dispatch($data['color']);

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
        $images = [];
        foreach ($data as $image)
            $images[] = new Image($image);

        return $images;
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
}
