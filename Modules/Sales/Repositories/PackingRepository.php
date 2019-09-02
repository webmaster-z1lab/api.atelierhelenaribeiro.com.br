<?php

namespace Modules\Sales\Repositories;

use Modules\Employee\Models\EmployeeTypes;
use Modules\Sales\Jobs\CheckOutProducts;
use Modules\Sales\Models\Packing;
use Modules\Stock\Models\Product;
use Modules\Stock\Models\ProductStatus;

class PackingRepository
{
    /**
     * @param  bool  $paginate
     * @param  int   $items
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function all(bool $paginate = TRUE, int $items = 10)
    {
        if (\Auth::user()->type === EmployeeTypes::TYPE_ADMIN) {
            return Packing::latest()->take(30)->get();
        }

        return Packing::where('seller_id', \Auth::id())->latest()->take(30)->get();
    }

    /**
     * @param  array  $data
     *
     * @return \Modules\Sales\Models\Packing
     */
    public function create(array $data): Packing
    {
        if (Packing::where('seller_id', $data['seller'])
            ->where(function ($query) {
                $query->where('checked_out_at', 'exists', FALSE)->orWhereNull('checked_out_at');
            })->exists()) {
            abort(400, 'Já existe um romaneio em aberto para esse vendedor.');
        }

        foreach ($data['products'] as $key => $item) {
            $data['products'][$key]['amount'] = $item['amount'] = intval($item['amount']);
            if (Product::where('reference', $item['reference'])->where('status', ProductStatus::AVAILABLE_STATUS)->count() < $item['amount']) {
                abort(400, "Não há peças suficientes do produto {$item['reference']}.");
            }
        }

        $packing = new Packing();

        foreach ($this->createProducts($data['products']) as $product) {
            $packing->products()->associate($product);
        }
        $packing->seller()->associate($data['seller']);

        $packing->save();

        return $packing;
    }

    /**
     * @param  array                          $data
     * @param  \Modules\Sales\Models\Packing  $packing
     *
     * @return \Modules\Sales\Models\Packing
     */
    public function update(array $data, Packing $packing): Packing
    {
        if (Packing::where('_id', '<>', $packing->id)
            ->where('seller_id', $data['seller'])
            ->where(function ($query) {
                $query->where('checked_out_at', 'exists', FALSE)->orWhereNull('checked_out_at');
            })->exists()) {
            abort(400, 'Já existe um romaneio em aberto para esse vendedor.');
        }

        foreach ($data['products'] as $key => $item) {
            $data['products'][$key]['amount'] = $item['amount'] = intval($item['amount']);
            $available = Product::where('reference', $item['reference'])->where('status', ProductStatus::AVAILABLE_STATUS)->count();
            $available += $packing->products()->where('reference', $item['reference'])->count();
            if ($available < $item['amount']) {
                abort(400, "Não há peças suficientes do produto {$item['reference']}.");
            }
        }

        $this->releaseProducts($packing);

        $packing->seller()->associate($data['seller']);
        foreach ($this->createProducts($data['products']) as $product) {
            $packing->products()->associate($product);
        }

        $packing->save();

        return  $packing;
    }

    /**
     * @param  \Modules\Sales\Models\Packing  $packing
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Packing $packing)
    {
        $this->releaseProducts($packing);

        return $packing->delete();
    }

    /**
     * @param  array                          $data
     * @param  \Modules\Sales\Models\Packing  $packing
     *
     * @return \Modules\Sales\Models\Packing
     */
    public function checkOut(array $data, Packing $packing)
    {
        foreach ($data['checked'] as $checked) {
            $expected = $packing->products()
                ->where('reference', $checked['reference'])
                ->whereIn('status', [ProductStatus::IN_TRANSIT_STATUS, ProductStatus::RETURNED_STATUS])
                ->count();
            if ($expected !== intval($checked['amount'])) {
                abort(400, "A quantidade informada do produto {$checked['reference']} é diferente da esperada.");
            }
        }

        $packing->checked_out_at = now();
        $packing->save();

        CheckOutProducts::dispatch($packing);

        return $packing;
    }

    /**
     * @param  array  $data
     *
     * @return array
     */
    private function createProducts(array $data): array
    {
        $products = [];
        foreach ($data as $item) {
            $product = Product::where('reference', $item['reference'])
                ->where('status', ProductStatus::AVAILABLE_STATUS)
                ->latest()->take($item['amount'])->get();

            $product->each(function (Product $product, int $key) use (&$products) {
                $products[] = new \Modules\Sales\Models\Product([
                    'product_id' => $product->id,
                    'reference'  => $product->reference,
                    'thumbnail'  => $product->thumbnail,
                    'size'       => $product->size,
                    'color'      => $product->color,
                    'price'      => $product->price->price,
                ]);
            });

            Product::whereIn('_id', $product->modelKeys())->update(['status' => ProductStatus::IN_TRANSIT_STATUS]);
        }

        return $products;
    }

    /**
     * @param  \Modules\Sales\Models\Packing  $packing
     */
    private function releaseProducts(Packing $packing)
    {
        Product::whereIn('_id', $packing->products->pluck('product_id')->all())
            ->update(['status' => ProductStatus::AVAILABLE_STATUS]);

        $packing->products()->dissociate($packing->products->modelKeys());
    }
}
