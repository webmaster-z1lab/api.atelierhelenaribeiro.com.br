<?php

namespace Modules\Stock\Repositories;

use App\Models\Price;
use App\Repositories\ImageRepository;
use App\Traits\Reference;
use Illuminate\Support\Collection;
use Modules\Catalog\Models\Template;
use Modules\Stock\Models\Color;
use Modules\Stock\Models\Product;
use Modules\Stock\Models\ProductStatus;
use Modules\Stock\Models\Size;

class ProductRepository
{
    use Reference;

    /**
     * @param  int  $limit
     *
     * @return \Illuminate\Database\Query\Expression
     */
    public function all(int $limit = 30)
    {
        return Product::raw(function ($collection) use ($limit){
            return $collection->aggregate([
                [
                    '$match' => [
                        'status' => ProductStatus::AVAILABLE_STATUS,
                    ]
                ],
                [
                    '$group' => [
                        '_id'      => '$reference',
                        'count'    => ['$sum' => 1],
                        'products' => ['$push' => '$$ROOT'],
                    ],
                ],
                ['$limit' => $limit],
            ]);
        });
    }

    /**
     * @param  array  $data
     *
     * @return \Illuminate\Support\Collection
     */
    public function create(array $data): Collection
    {
        $products = collect();
        $template = Template::find($data['template']);
        $price = $this->createPrice($data, $template);
        /** @var \Modules\Stock\Models\Size $size */
        $size = Size::where('name', $data['size'])->first();
        $color = Color::where('name', $data['color'])->first();
        if (is_null($color)) {
            $colorRepository = new ColorRepository();
            $color = $colorRepository->create(['name' => $data['color']]);
        }

        $images = isset($data['images']) ? (new ImageRepository)->createMany($data['images'], $template) : [];

        $data['amount'] = intval($data['amount']);
        $data['reference'] = implode('-', [$template->reference, $color->reference, $size->reference]);

        for ($i = 0; $i < $data['amount']; $i++) {
            $products->add($this->createProduct($data, $template, $price, $images));
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
        if (isset($data['price']) && $product->price->price !== intval($data['price'])) {
            $product->prices()->associate($this->createPrice($data));
        }

        $this->createImages($data, $product, $product->template);

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
     * @param  array                             $data
     * @param  array                             $images
     * @param  \Modules\Catalog\Models\Template  $template
     * @param  \App\Models\Price                 $price
     *
     * @return \Modules\Stock\Models\Product
     */
    public function createProduct(array $data, Template $template, Price $price, array $images = []): Product
    {
        $data['barcode'] = $this->getNewReference(Product::class, Product::BARCODE_LENGTH, 'barcode');

        $product = new Product($data);

        $product->template()->associate($template);
        $product->prices()->associate($price);
        $product->save();

        if (!empty($images) || (isset($data['template_images']) && filled($data['template_images']))) {
            if (!empty($images)) {
                $product->images()->saveMany($images);
            } else {
                $product->images()->sync($data['template_images']);
            }
        } else {
            $template->images->each(function ($item, $key) use ($product) {
                /** @var \App\Models\Image $item */
                $item->products()->attach($product);
            });
        }

        return $product;
    }

    /**
     * @param  array                             $data
     * @param  \Modules\Stock\Models\Product     $product
     * @param  \Modules\Catalog\Models\Template  $template
     */
    private function createImages(array $data, Product $product, Template $template): void
    {
        if (isset($data['template_images']) && filled($data['template_images'])) {
            $product->images()->sync($data['template_images']);
        }

        if (isset($data['images'])) {
            $product->images()->saveMany((new ImageRepository)->createMany($data['images'], $template));
        }
    }

    /**
     * @param  array                             $data
     * @param  \Modules\Catalog\Models\Template  $template
     *
     * @return \App\Models\Price
     */
    private function createPrice(array $data, Template $template = NULL): Price
    {
        $price = isset($data['price']) ? intval($data['price']) : $template->price->price;

        return new Price([
            'price'      => $price,
            'started_at' => now(),
        ]);
    }
}
