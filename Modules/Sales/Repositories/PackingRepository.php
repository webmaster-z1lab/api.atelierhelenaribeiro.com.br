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
        foreach ($data['products'] as $item) {
            if (Product::where('reference', $item['reference'])->where('status', ProductStatus::AVAILABLE_STATUS)->count() < intval($item['amount'])) {
                abort(400, "Não há peças suficientes do produto {$item['reference']}.");
            }
        }

        $products = $this->createProducts($data['products']);

        $packing = new Packing();

        $packing->seller()->associate($data['seller']);
        $packing->products()->saveMany($products);

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
        foreach ($data['products'] as $item) {
            $available = Product::where('reference', $item['reference'])->where('status', ProductStatus::AVAILABLE_STATUS)->count();
            $available += $packing->products()->where('reference', $item['reference'])->sum('amount');
            if ($available < intval($item['amount'])) {
                abort(400, "Não há peças suficientes do produto {$item['reference']}.");
            }
        }

        $packing->seller()->associate($data['seller']);
        $packing->products()->delete();
        $packing->products()->createMany($this->createProducts($data['products']));

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
            $checked['amount'] = intval($checked['amount']);
            /** @var \Modules\Sales\Models\Product $product */
            $product = $packing->products()->where('reference', $checked['reference'])->first();
            if ($checked['amount'] !== ($product->amount + $product->returned - $product->sold)) {
                abort(400, 'A quantidade informada é diferente da esperada.');
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
        foreach ($data as $key => $item) {
            $item['amount'] = intval($item['amount']);
            $product = Product::where('reference', $item['reference'])
                ->where('status', ProductStatus::AVAILABLE_STATUS)
                ->latest()->take($data['amount'])->get();

            $products[$key] = new \Modules\Sales\Models\Product([
                'reference' => $item['reference'],
                'thumbnail' => $product->first->thumbnail,
                'size' => $product->first->size,
                'color' => $product->first->color,
                'price' => $product->first->price->price,
                'amount' => $item['amount'],
            ]);

            $ids = $product->pluck('_id')->toArray();

            Product::whereIn('_id', $ids)->update(['status' => ProductStatus::IN_TRANSIT_STATUS]);
        }

        return $products;
    }
}
